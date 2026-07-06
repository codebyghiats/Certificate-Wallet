@extends('layouts.app')

@section('title', $certificate->title . ' - Certificate Wallet')
@section('page-subtitle', 'Detail Dokumen')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('certificates.index') }}" class="w-9 h-9 bg-slate-800/50 border border-slate-700/50 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700/50 transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div class="flex-1 min-w-0">
            <h2 class="text-lg font-bold text-white truncate">{{ $certificate->title }}</h2>
            <div class="flex flex-wrap gap-1 mt-1">
                @foreach($certificate->categories as $cat)
                    @php
                        $iconColors = [
                            'id-card' => 'text-blue-400 bg-blue-500/10',
                            'monitor-check' => 'text-emerald-400 bg-emerald-500/10',
                            'graduation-cap' => 'text-purple-400 bg-purple-500/10',
                            'trophy' => 'text-amber-400 bg-amber-500/10',
                        ];
                        $ic = $iconColors[$cat->icon] ?? 'text-slate-400 bg-slate-500/10';
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 {{ $ic }} rounded-full text-[10px] font-medium">
                        <i data-lucide="{{ $cat->icon ?? 'folder' }}" class="w-3 h-3"></i>
                        {{ $cat->name }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('certificates.edit', $certificate) }}" class="w-9 h-9 bg-slate-800/50 border border-slate-700/50 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-400 hover:border-blue-500/30 transition-all duration-200">
                <i data-lucide="pencil" class="w-4 h-4"></i>
            </a>
            <form method="POST" action="{{ route('certificates.destroy', $certificate) }}" onsubmit="return confirm('Yakin ingin menghapus sertifikat ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-9 h-9 bg-slate-800/50 border border-slate-700/50 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-400 hover:border-red-500/30 transition-all duration-200">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- File Preview -->
    <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-800/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="{{ $certificate->file_type === 'pdf' ? 'file-text' : 'image' }}"
                   class="w-4 h-4 {{ $certificate->file_type === 'pdf' ? 'text-red-400' : 'text-blue-400' }}"></i>
                <span class="text-sm text-slate-300 font-medium">Pratinjau Dokumen</span>
                <span class="px-2 py-0.5 bg-slate-800 rounded-full text-[10px] text-slate-400 uppercase">{{ $certificate->file_type }}</span>
            </div>
            <a href="{{ route('certificates.preview', $certificate) }}" target="_blank"
               class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800/50 border border-slate-700/50 rounded-lg text-xs text-slate-400 hover:text-white hover:bg-slate-700/50 transition-all duration-200">
                <i data-lucide="external-link" class="w-3 h-3"></i>
                Buka Baru
            </a>
        </div>

        <div class="relative bg-slate-950 flex items-center justify-center min-h-[300px] lg:min-h-[500px]">
            @if($certificate->file_type === 'pdf')
                <iframe src="{{ route('certificates.preview', $certificate) }}"
                        class="w-full h-[400px] lg:h-[600px]"
                        frameborder="0"></iframe>
            @else
                <img src="{{ route('certificates.preview', $certificate) }}"
                     alt="{{ $certificate->title }}"
                     class="max-w-full max-h-[500px] object-contain p-4 cursor-zoom-in"
                     onclick="openFullscreen(this.src)">
            @endif
        </div>
    </div>

    <!-- Document Details -->
    <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl p-5 space-y-4">
        <h3 class="text-sm font-semibold text-slate-300">Detail Dokumen</h3>

        <div class="space-y-3">
            <!-- Categories -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-violet-500/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="tags" class="w-4 h-4 text-violet-400"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Kategori</p>
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @foreach($certificate->categories as $cat)
                            @php
                                $catIconColors = [
                                    'id-card' => 'text-blue-400 bg-blue-500/10',
                                    'monitor-check' => 'text-emerald-400 bg-emerald-500/10',
                                    'graduation-cap' => 'text-purple-400 bg-purple-500/10',
                                    'trophy' => 'text-amber-400 bg-amber-500/10',
                                ];
                                $cc = $catIconColors[$cat->icon] ?? 'text-slate-400 bg-slate-500/10';
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $cc }} rounded-lg text-xs font-medium">
                                <i data-lucide="{{ $cat->icon ?? 'folder' }}" class="w-3 h-3"></i>
                                {{ $cat->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Issuer -->
            @if($certificate->issuer)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="building" class="w-4 h-4 text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Penerbit</p>
                        <p class="text-sm text-slate-200">{{ $certificate->issuer }}</p>
                    </div>
                </div>
            @endif

            <!-- Expiry Date -->
            @if($certificate->expired_at)
                @php $status = $certificate->getExpiryStatus(); @endphp
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5
                        {{ $status === 'expired' ? 'bg-red-500/10' : ($status === 'expiring_soon' ? 'bg-amber-500/10' : 'bg-emerald-500/10') }}">
                        <i data-lucide="calendar" class="w-4 h-4
                            {{ $status === 'expired' ? 'text-red-400' : ($status === 'expiring_soon' ? 'text-amber-400' : 'text-emerald-400') }}"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Kedaluwarsa</p>
                        <p class="text-sm {{ $status === 'expired' ? 'text-red-400' : ($status === 'expiring_soon' ? 'text-amber-400' : 'text-slate-200') }}">
                            {{ $certificate->expired_at->format('d M Y') }}
                            @if($status === 'expired')
                                <span class="text-xs text-red-400/70">(Sudah kedaluwarsa)</span>
                            @elseif($status === 'expiring_soon')
                                <span class="text-xs text-amber-400/70">({{ $certificate->expired_at->diffForHumans() }})</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- Upload Date -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-slate-800/50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Diunggah</p>
                    <p class="text-sm text-slate-200">{{ $certificate->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <!-- Notes -->
            @if($certificate->notes)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-slate-800/50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="sticky-note" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Catatan</p>
                        <p class="text-sm text-slate-300 whitespace-pre-line">{{ $certificate->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Fullscreen Image Modal -->
<div id="fullscreen-modal" class="hidden fixed inset-0 z-[70] bg-slate-950/95 flex items-center justify-center p-4 cursor-zoom-out" onclick="closeFullscreen()">
    <img id="fullscreen-img" src="" alt="" class="max-w-full max-h-full object-contain">
    <button class="absolute top-4 right-4 w-10 h-10 bg-slate-800/80 rounded-full flex items-center justify-center text-white hover:bg-slate-700 transition-colors">
        <i data-lucide="x" class="w-5 h-5"></i>
    </button>
</div>
@endsection

@section('scripts')
<script>
    function openFullscreen(src) {
        document.getElementById('fullscreen-img').src = src;
        document.getElementById('fullscreen-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFullscreen() {
        document.getElementById('fullscreen-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeFullscreen();
    });
</script>
@endsection
