@extends('layouts.app')

@section('title', 'Dashboard - Certificate Wallet')
@section('page-subtitle', 'Ringkasan Dokumen')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div>
        <h2 class="text-xl font-bold text-white">Halo, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-sm text-slate-400 mt-1">Berikut ringkasan dokumen digital Anda.</p>
    </div>

    <!-- Stats Cards -->

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-4">
        <!-- Total Documents -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl p-4 lg:p-5 group hover:border-violet-500/30 transition-all duration-300">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-500/10 rounded-xl flex items-center justify-center group-hover:bg-violet-500/20 transition-colors duration-300">
                    <i data-lucide="file-text" class="w-5 h-5 text-violet-400"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $totalCertificates }}</p>
            <p class="text-xs text-slate-500 mt-1">Total Dokumen</p>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl p-4 lg:p-5 group hover:border-amber-500/30 transition-all duration-300">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center group-hover:bg-amber-500/20 transition-colors duration-300">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-400"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $expiringSoon->count() }}</p>
            <p class="text-xs text-slate-500 mt-1">Segera Kedaluwarsa</p>
        </div>

        <!-- Expired -->
        <div class="col-span-2 lg:col-span-1 bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl p-4 lg:p-5 group hover:border-red-500/30 transition-all duration-300">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-red-500/10 rounded-xl flex items-center justify-center group-hover:bg-red-500/20 transition-colors duration-300">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-400"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $expired->count() }}</p>
            <p class="text-xs text-slate-500 mt-1">Sudah Kedaluwarsa</p>
        </div>
    </div>

    <!-- Expiring Soon Alert -->
    @if($expiringSoon->count() > 0)
        <div class="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="bell-ring" class="w-4 h-4 text-amber-400"></i>
                <h3 class="text-sm font-semibold text-amber-300">Perhatian! Dokumen Segera Kedaluwarsa</h3>
            </div>
            <div class="space-y-2">
                @foreach($expiringSoon as $cert)
                    <a href="{{ route('certificates.show', $cert) }}" class="flex items-center justify-between px-3 py-2 bg-slate-900/50 rounded-xl hover:bg-slate-800/50 transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center">
                                <i data-lucide="{{ $cert->file_type === 'pdf' ? 'file-text' : 'image' }}" class="w-4 h-4 text-amber-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-200 group-hover:text-white transition-colors">{{ $cert->title }}</p>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    @foreach($cert->categories as $cat)
                                        @php
                                            $catColors = [
                                                'id-card' => 'text-blue-400',
                                                'monitor-check' => 'text-emerald-400',
                                                'graduation-cap' => 'text-purple-400',
                                                'trophy' => 'text-amber-400',
                                            ];
                                            $ctCol = $catColors[$cat->icon] ?? 'text-slate-400';
                                        @endphp
                                        <span class="text-[10px] {{ $ctCol }}">{{ $cat->name }}{{ !$loop->last ? ',' : '' }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-amber-400 font-medium">{{ $cert->expired_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Expired Alert -->
    @if($expired->count() > 0)
        <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
                <h3 class="text-sm font-semibold text-red-300">Dokumen Sudah Kedaluwarsa</h3>
            </div>
            <div class="space-y-2">
                @foreach($expired as $cert)
                    <a href="{{ route('certificates.show', $cert) }}" class="flex items-center justify-between px-3 py-2 bg-slate-900/50 rounded-xl hover:bg-slate-800/50 transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                                <i data-lucide="{{ $cert->file_type === 'pdf' ? 'file-text' : 'image' }}" class="w-4 h-4 text-red-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-200 group-hover:text-white transition-colors">{{ $cert->title }}</p>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    @foreach($cert->categories as $cat)
                                        @php
                                            $catColors = [
                                                'id-card' => 'text-blue-400',
                                                'monitor-check' => 'text-emerald-400',
                                                'graduation-cap' => 'text-purple-400',
                                                'trophy' => 'text-amber-400',
                                            ];
                                            $ctCol = $catColors[$cat->icon] ?? 'text-slate-400';
                                        @endphp
                                        <span class="text-[10px] {{ $ctCol }}">{{ $cat->name }}{{ !$loop->last ? ',' : '' }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-red-400 font-medium">Kedaluwarsa {{ $cert->expired_at->format('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Categories Overview -->
    <div>
        <h3 class="text-sm font-semibold text-slate-300 mb-3">Kategori Dokumen</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @php
                $categoryIcons = [
                    'id-card' => 'id-card',
                    'monitor-check' => 'monitor-check',
                    'graduation-cap' => 'graduation-cap',
                    'trophy' => 'trophy',
                ];
                $categoryColors = [
                    'id-card' => ['text-blue-400', 'bg-blue-500/10', 'border-blue-500/30', 'hover:border-blue-500/50'],
                    'monitor-check' => ['text-emerald-400', 'bg-emerald-500/10', 'border-emerald-500/30', 'hover:border-emerald-500/50'],
                    'graduation-cap' => ['text-purple-400', 'bg-purple-500/10', 'border-purple-500/30', 'hover:border-purple-500/50'],
                    'trophy' => ['text-amber-400', 'bg-amber-500/10', 'border-amber-500/30', 'hover:border-amber-500/50'],
                ];
            @endphp

            @foreach($categories as $category)
                @php
                    $colors = $categoryColors[$category->icon] ?? ['text-slate-400', 'bg-slate-500/10', 'border-slate-500/30', 'hover:border-slate-500/50'];
                @endphp
                <a href="{{ route('certificates.index', ['categories' => $category->id]) }}"
                   class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl p-4 group {{ $colors[3] }} transition-all duration-300 hover:-translate-y-0.5">
                    <div class="w-10 h-10 {{ $colors[1] }} rounded-xl flex items-center justify-center mb-3">
                        <i data-lucide="{{ $category->icon ?? 'folder' }}" class="w-5 h-5 {{ $colors[0] }}"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-200">{{ $category->name }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $category->certificates_count }} dokumen</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Documents -->
    @if($certificates->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-slate-300">Dokumen Terbaru</h3>
                <a href="{{ route('certificates.index') }}" class="text-xs text-violet-400 hover:text-violet-300 font-medium transition-colors">Lihat Semua →</a>
            </div>
            <div class="space-y-2">
                @foreach($certificates->take(5) as $cert)
                    <a href="{{ route('certificates.show', $cert) }}" class="flex items-center gap-3 px-4 py-3 bg-slate-900/50 border border-slate-800/50 rounded-xl hover:bg-slate-800/30 hover:border-slate-700/50 transition-all duration-200 group">
                        <!-- File Type Icon -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                            {{ $cert->file_type === 'pdf' ? 'bg-red-500/10' : 'bg-blue-500/10' }}">
                            <i data-lucide="{{ $cert->file_type === 'pdf' ? 'file-text' : 'image' }}"
                               class="w-5 h-5 {{ $cert->file_type === 'pdf' ? 'text-red-400' : 'text-blue-400' }}"></i>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-200 group-hover:text-white transition-colors truncate">{{ $cert->title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($cert->categories as $cat)
                                        @php
                                            $catColors = [
                                                'id-card' => 'text-blue-400',
                                                'monitor-check' => 'text-emerald-400',
                                                'graduation-cap' => 'text-purple-400',
                                                'trophy' => 'text-amber-400',
                                            ];
                                            $ctCol = $catColors[$cat->icon] ?? 'text-slate-400';
                                        @endphp
                                        <span class="text-[10px] {{ $ctCol }}">{{ $cat->name }}{{ !$loop->last ? ',' : '' }}</span>
                                    @endforeach
                                </div>
                                @if($cert->issuer)
                                    <span class="text-[10px] text-slate-600">•</span>
                                    <span class="text-[10px] text-slate-500 truncate">{{ $cert->issuer }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @php $status = $cert->getExpiryStatus(); @endphp
                        @if($status === 'expired')
                            <span class="px-2 py-1 bg-red-500/10 text-red-400 text-[10px] font-medium rounded-lg shrink-0">Expired</span>
                        @elseif($status === 'expiring_soon')
                            <span class="px-2 py-1 bg-amber-500/10 text-amber-400 text-[10px] font-medium rounded-lg shrink-0">{{ $cert->expired_at->diffForHumans() }}</span>
                        @endif

                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600 shrink-0 group-hover:text-slate-400 transition-colors"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="folder-open" class="w-8 h-8 text-slate-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-300 mb-2">Belum ada dokumen</h3>
            <p class="text-sm text-slate-500 mb-6">Mulai simpan sertifikat dan dokumen penting Anda.</p>
            <a href="{{ route('certificates.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-500 to-indigo-600 text-white font-medium px-6 py-3 rounded-xl text-sm transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/30 hover:-translate-y-0.5">
                <i data-lucide="upload" class="w-4 h-4"></i>
                Upload Dokumen Pertama
            </a>
        </div>
    @endif
</div>
@endsection
