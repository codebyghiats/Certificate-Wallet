@extends('layouts.app')

@section('title', 'Upload Dokumen - Certificate Wallet')
@section('page-subtitle', 'Upload Baru')

@section('content')
<div class="max-w-lg mx-auto space-y-5">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('certificates.index') }}" class="w-9 h-9 bg-slate-800/50 border border-slate-700/50 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700/50 transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-lg font-bold text-white">Upload Dokumen</h2>
            <p class="text-xs text-slate-500">Tambahkan sertifikat atau dokumen baru</p>
        </div>
    </div>

    <!-- Upload Form -->
    <form method="POST" action="{{ route('certificates.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- File Upload Area -->
        <div class="relative">
            <label for="file-upload" class="block cursor-pointer">
                <div id="drop-zone" class="bg-slate-900/50 border-2 border-dashed border-slate-700/50 rounded-2xl p-8 text-center hover:border-violet-500/30 hover:bg-violet-500/5 transition-all duration-300">
                    <div class="w-14 h-14 bg-violet-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="cloud-upload" class="w-7 h-7 text-violet-400"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-300 mb-1" id="file-label">Pilih atau seret file di sini</p>
                    <p class="text-xs text-slate-500">PDF, PNG, JPG, JPEG (Maks. 5MB)</p>
                </div>
            </label>
            <input type="file" id="file-upload" name="file" accept=".pdf,.png,.jpg,.jpeg" class="hidden" required>
            @error('file')
                <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Title -->
        <div>
            <label for="title" class="block text-xs font-medium text-slate-400 mb-2">
                Judul Dokumen <span class="text-red-400">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                class="w-full bg-slate-900/50 border border-slate-800/50 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500/30 transition-all duration-300"
                placeholder="Contoh: Sertifikat AWS Cloud Practitioner">
            @error('title')
                <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Categories (Multiple) -->
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">
                Kategori <span class="text-red-400">*</span>
                <span class="text-slate-600 text-xs">(Pilih satu atau lebih)</span>
            </label>
            <div class="space-y-2 max-h-60 overflow-y-auto p-2 bg-slate-900/30 border border-slate-800/50 rounded-xl">
                @foreach($categories as $category)
                    @php
                        $categoryIcons = [
                            'id-card' => 'id-card',
                            'monitor-check' => 'monitor-check',
                            'graduation-cap' => 'graduation-cap',
                            'trophy' => 'trophy',
                        ];
                        $categoryColors = [
                            'id-card' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                            'monitor-check' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'graduation-cap' => 'bg-purple-500/10 text-purple-400 border-purple-500/30',
                            'trophy' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                        ];
                        $colors = $categoryColors[$category->icon] ?? 'bg-slate-500/10 text-slate-400 border-slate-500/30';
                    @endphp
                    <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800/30 cursor-pointer transition-colors">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                               class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-violet-500 focus:ring-violet-500/50 focus:ring-offset-0"
                               {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $colors }}">
                                <i data-lucide="{{ $category->icon ?? 'folder' }}" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm text-slate-200">{{ $category->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
            @enderror
            @error('categories.*')
                <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Issuer -->
        <div>
            <label for="issuer" class="block text-xs font-medium text-slate-400 mb-2">
                Penerbit <span class="text-slate-600">(opsional)</span>
            </label>
            <input type="text" id="issuer" name="issuer" value="{{ old('issuer') }}"
                class="w-full bg-slate-900/50 border border-slate-800/50 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500/30 transition-all duration-300"
                placeholder="Contoh: Cisco, Dicoding, Kemendikbud">
        </div>

        <!-- Expired At -->
        <div>
            <label for="expired_at" class="block text-xs font-medium text-slate-400 mb-2">
                Tanggal Kedaluwarsa <span class="text-slate-600">(opsional)</span>
            </label>
            <input type="date" id="expired_at" name="expired_at" value="{{ old('expired_at') }}"
                class="w-full bg-slate-900/50 border border-slate-800/50 rounded-xl px-4 py-3 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500/30 transition-all duration-300">
        </div>

        <!-- Notes -->
        <div>
            <label for="notes" class="block text-xs font-medium text-slate-400 mb-2">
                Catatan <span class="text-slate-600">(opsional)</span>
            </label>
            <textarea id="notes" name="notes" rows="3"
                class="w-full bg-slate-900/50 border border-slate-800/50 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500/30 transition-all duration-300 resize-none"
                placeholder="Nomor registrasi, link verifikasi, dll.">{{ old('notes') }}</textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-violet-500 to-indigo-600 text-white font-medium py-3.5 rounded-xl text-sm transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/30 hover:-translate-y-0.5 active:translate-y-0 active:shadow-none flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Upload Dokumen
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // File upload preview
    const fileInput = document.getElementById('file-upload');
    const fileLabel = document.getElementById('file-label');
    const dropZone = document.getElementById('drop-zone');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            fileLabel.textContent = `${file.name} (${sizeMB} MB)`;
            dropZone.classList.add('border-violet-500/50', 'bg-violet-500/5');
            dropZone.classList.remove('border-slate-700/50');
        }
    });

    // Drag and drop
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('border-violet-500/50', 'bg-violet-500/10');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-violet-500/50', 'bg-violet-500/10');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        fileInput.files = e.dataTransfer.files;
        fileInput.dispatchEvent(new Event('change'));
    });
</script>
@endsection
