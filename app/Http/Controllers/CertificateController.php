<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Get the storage disk to use based on FILESYSTEM_DISK env.
     */
    private function storageDisk(): string
    {
        return env('FILESYSTEM_DISK', 'local');
    }

    /**
     * Check if using S3 disk.
     */
    private function isS3(): bool
    {
        return $this->storageDisk() === 's3';
    }

    /**
     * Store a file and return the path.
     */
    private function storeFile($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $uuidFilename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs('certificates', $uuidFilename, $this->storageDisk());
        return $path;
    }

    /**
     * Delete a file from storage.
     */
    private function deleteFile(string $path): bool
    {
        return Storage::disk($this->storageDisk())->delete($path);
    }

    /**
     * Get the file content or response for preview.
     */
    private function getFileResponse(Certificate $certificate)
    {
        $disk = $this->storageDisk();

        if ($this->isS3()) {
            // Generate a temporary signed URL (valid for 1 hour)
            $url = Storage::disk('s3')->temporaryUrl(
                $certificate->file_path,
                now()->addHour()
            );

            // For S3, redirect to the signed URL
            return redirect()->away($url);
        }

        // Local storage: serve the file directly
        $filePath = storage_path('app/private/' . $certificate->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $mimeTypes = [
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        ];

        $mime = $mimeTypes[$certificate->file_type] ?? 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mime,
        ]);
    }

    /**
     * Display the dashboard with overview stats.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $certificates = $user->certificates()->with('categories')->latest()->get();
        $categories = Category::withCount(['certificates' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $totalCertificates = $certificates->count();

        $expiringSoon = $certificates->filter(function ($cert) {
            return $cert->getExpiryStatus() === 'expiring_soon';
        });

        $expired = $certificates->filter(function ($cert) {
            return $cert->getExpiryStatus() === 'expired';
        });

        return view('dashboard', compact(
            'certificates',
            'categories',
            'totalCertificates',
            'expiringSoon',
            'expired'
        ));
    }

    /**
     * Display certificates list, optionally filtered by categories or search.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->certificates()->with('categories');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('issuer', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter by multiple categories (OR logic)
        if ($request->filled('categories')) {
            $categoryIds = is_array($request->categories)
                ? $request->categories
                : explode(',', $request->categories);

            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $certificates = $query->latest()->get();
        $categories = Category::all();
        $selectedCategories = $request->filled('categories')
            ? (is_array($request->categories) ? $request->categories : explode(',', $request->categories))
            : [];

        return view('certificates.index', compact('certificates', 'categories', 'selectedCategories'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create()
    {
        $categories = Category::all();
        return view('certificates.create', compact('categories'));
    }

    /**
     * Store a newly created certificate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'expired_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'file' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ]);

        // Store file to S3 (or local depending on env)
        $file = $request->file('file');
        $path = $this->storeFile($file);

        $certificate = Auth::user()->certificates()->create([
            'title' => $validated['title'],
            'issuer' => $validated['issuer'],
            'file_path' => $path,
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'expired_at' => $validated['expired_at'],
            'notes' => $validated['notes'],
        ]);

        // Attach categories (many-to-many)
        $certificate->categories()->attach($validated['categories']);

        return redirect()->route('certificates.index')
            ->with('success', 'Sertifikat berhasil diunggah!');
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        $certificate->load('categories');

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        $certificate->load('categories');

        return view('certificates.edit', compact('certificate', 'categories'));
    }

    /**
     * Update the specified certificate.
     */
    public function update(Request $request, Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'issuer' => ['nullable', 'string', 'max:255'],
            'expired_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ]);

        // If new file is uploaded, replace old one
        if ($request->hasFile('file')) {
            // Delete old file from storage
            $this->deleteFile($certificate->file_path);

            $file = $request->file('file');
            $path = $this->storeFile($file);

            $certificate->file_path = $path;
            $certificate->file_type = strtolower($file->getClientOriginalExtension());
        }

        $certificate->title = $validated['title'];
        $certificate->issuer = $validated['issuer'];
        $certificate->expired_at = $validated['expired_at'];
        $certificate->notes = $validated['notes'];
        $certificate->save();

        // Sync categories (many-to-many)
        $certificate->categories()->sync($validated['categories']);

        return redirect()->route('certificates.index')
            ->with('success', 'Sertifikat berhasil diperbarui!');
    }

    /**
     * Remove the specified certificate.
     */
    public function destroy(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete file from storage
        $this->deleteFile($certificate->file_path);

        // Detach pivot relationships
        $certificate->categories()->detach();

        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus!');
    }

    /**
     * Serve or redirect to the certificate file securely.
     */
    public function preview(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        return $this->getFileResponse($certificate);
    }
}
