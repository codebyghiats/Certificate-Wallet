@extends('layouts.app')

@section('title', 'Semua Dokumen - Certificate Wallet')
@section('page-subtitle', 'Dokumen Digital')

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-white">Semua Dokumen</h2>
            <p class="text-xs text-slate-500 mt-0.5">{{ $certificates->count() }} dokumen ditemukan</p>
        </div>
        <a href="{{ route('certificates.create') }}" class="hidden lg:inline-flex items-center gap-2 bg-gradient-to-r from-violet-500 to-indigo-600 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/30 hover:-translate-y-0.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Upload Baru
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex flex-col sm:flex-row gap-3">
        <form action="{{ route('certificates.index') }}" method="GET" class="flex-1 flex gap-2">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penerbit, catatan..."
                    class="w-full bg-slate-900/50 border border-slate-800/50 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500/30 transition-all duration-300">
            </div>
            <input type="hidden" name="categories" id="categories-input" value="{{ request('categories') }}">
            <button type="submit" class="px-4 py-2.5 bg-slate-800/50 border border-slate-700/50 rounded-xl text-sm text-slate-300 hover:bg-slate-700/50 transition-all duration-200">
                Cari
            </button>
        </form>
    </div>

    <!-- Multi-Category Filter Pills -->
    <div x-data="{ open: false }">
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <button type="button" onclick="document.getElementById('categories-modal').classList.remove('hidden')"
               class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200
               {{ count($selectedCategories) > 0 ? 'bg-violet-500/20 text-violet-300 border border-violet-500/30' : 'bg-slate-800/50 text-slate-400 border border-slate-700/50 hover:border-slate-600/50' }}">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                Kategori
                @if(count($selectedCategories) > 0)
                    <span class="flex items-center justify-center w-4 h-4 bg-violet-500/30 rounded-full text-[10px]">{{ count($selectedCategories) }}</span>
                @endif
            </button>

            @if(request('search'))
                <a href="{{ route('certificates.index', array_merge(request()->except('search', '_token'))) }}"
                   class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-slate-800/50 text-slate-400 border border-slate-700/50 hover:border-red-500/30 hover:text-red-400 transition-all duration-200">
                    <i data-lucide="x" class="w-3 h-3"></i>
                    "{{ request('search') }}"
                </a>
            @endif

            @foreach($selectedCategories as $catId)
                @php $cat = $categories->firstWhere('id', $catId); @endphp
                @if($cat)
                    <span class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium bg-violet-500/20 text-violet-300 border border-violet-500/30">
                        {{ $cat->name }}
                    </span>
                @endif
            @endforeach

            @if(count($selectedCategories) > 0 || request('search'))
                <a href="{{ route('certificates.index') }}"
                   class="shrink-0 text-[10px] text-slate-500 hover:text-slate-300 transition-colors">
                    Reset
                </a>
            @endif
        </div>

        <!-- Categories Modal for Multi-Select -->
        <div id="categories-modal" class="hidden fixed inset-0 z-[60] bg-slate-950/80 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="flex items-start justify-center pt-20 px-4">
                <div class="w-full max-w-md bg-slate-900 rounded-2xl border border-slate-800/50 shadow-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800/50">
                        <h3 class="text-sm font-semibold text-slate-200">Filter Kategori</h3>
                        <button type="button" onclick="document.getElementById('categories-modal').classList.add('hidden')" class="text-slate-500 hover:text-slate-300">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-4 space-y-2 max-h-80 overflow-y-auto">
                        @foreach($categories as $category)
                            @php
                                $catColors = [
                                    'id-card' => 'bg-blue-500/10 text-blue-400',
                                    'monitor-check' => 'bg-emerald-500/10 text-emerald-400',
                                    'graduation-cap' => 'bg-purple-500/10 text-purple-400',
                                    'trophy' => 'bg-amber-500/10 text-amber-400',
                                ];
                                $color = $catColors[$category->icon] ?? 'bg-slate-500/10 text-slate-400';
                            @endphp
                            <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800/30 cursor-pointer transition-colors">
                                <input type="checkbox" value="{{ $category->id }}"
                                       class="category-filter-checkbox w-4 h-4 rounded bg-slate-800 border-slate-700 text-violet-500 focus:ring-violet-500/50 focus:ring-offset-0"
                                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $color }}">
                                    <i data-lucide="{{ $category->icon ?? 'folder' }}" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="text-sm text-slate-200">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between px-5 py-4 border-t border-slate-800/50">
                        <button type="button" onclick="resetCategoryFilter()" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">
                            Reset
                        </button>
                        <button type="button" onclick="applyCategoryFilter()" class="px-5 py-2 bg-gradient-to-r from-violet-500 to-indigo-600 text-white text-xs font-medium rounded-lg hover:shadow-lg hover:shadow-violet-500/30 transition-all duration-300">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Grid -->
    @if($certificates->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($certificates as $cert)
                @php $status = $cert->getExpiryStatus(); @endphp
                <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl overflow-hidden group hover:border-slate-700/50 transition-all duration-300 hover:-translate-y-0.5">
                    <!-- Card Header with Status -->
                    <div class="relative p-4 pb-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                                    {{ $cert->file_type === 'pdf' ? 'bg-red-500/10' : 'bg-blue-500/10' }}">
                                    <i data-lucide="{{ $cert->file_type === 'pdf' ? 'file-text' : 'image' }}"
                                       class="w-5 h-5 {{ $cert->file_type === 'pdf' ? 'text-red-400' : 'text-blue-400' }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors truncate">{{ $cert->title }}</h3>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($cert->categories as $cat)
                                            @php
                                                $catIconColors = [
                                                    'id-card' => 'text-blue-400',
                                                    'monitor-check' => 'text-emerald-400',
                                                    'graduation-cap' => 'text-purple-400',
                                                    'trophy' => 'text-amber-400',
                                                ];
                                                $iconColor = $catIconColors[$cat->icon] ?? 'text-slate-400';
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-slate-800/50 rounded text-[9px] {{ $iconColor }} font-medium">
                                                <i data-lucide="{{ $cat->icon ?? 'folder' }}" class="w-2.5 h-2.5"></i>
                                                {{ $cat->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            @if($status === 'expired')
                                <span class="px-2 py-0.5 bg-red-500/10 text-red-400 text-[10px] font-medium rounded-full shrink-0">Expired</span>
                            @elseif($status === 'expiring_soon')
                                <span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 text-[10px] font-medium rounded-full shrink-0 animate-pulse">Segera</span>
                            @elseif($status === 'active')
                                <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[10px] font-medium rounded-full shrink-0">Aktif</span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Details -->
                    <div class="px-4 pb-3 space-y-2">
                        @if($cert->issuer)
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <i data-lucide="building" class="w-3 h-3 shrink-0"></i>
                                <span class="truncate">{{ $cert->issuer }}</span>
                            </div>
                        @endif
                        @if($cert->expired_at)
                            <div class="flex items-center gap-2 text-xs {{ $status === 'expired' ? 'text-red-400' : ($status === 'expiring_soon' ? 'text-amber-400' : 'text-slate-400') }}">
                                <i data-lucide="calendar" class="w-3 h-3 shrink-0"></i>
                                <span>{{ $cert->expired_at->format('d M Y') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <i data-lucide="clock" class="w-3 h-3 shrink-0"></i>
                            <span>Diunggah {{ $cert->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="flex items-center border-t border-slate-800/50 divide-x divide-slate-800/50">
                        <a href="{{ route('certificates.show', $cert) }}" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs text-slate-400 hover:text-violet-400 hover:bg-violet-500/5 transition-all duration-200">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            Lihat
                        </a>
                        <a href="{{ route('certificates.edit', $cert) }}" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs text-slate-400 hover:text-blue-400 hover:bg-blue-500/5 transition-all duration-200">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('certificates.destroy', $cert) }}" class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus sertifikat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs text-slate-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-200">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-slate-800/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="search-x" class="w-8 h-8 text-slate-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-300 mb-2">Tidak ada dokumen</h3>
            <p class="text-sm text-slate-500 mb-6">
                @if(request('search') || count($selectedCategories) > 0)
                    Coba ubah filter pencarian Anda.
                @else
                    Mulai simpan sertifikat dan dokumen penting Anda.
                @endif
            </p>
            @if(request('search') || count($selectedCategories) > 0)
                <a href="{{ route('certificates.index') }}" class="inline-flex items-center gap-2 text-violet-400 hover:text-violet-300 text-sm font-medium transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Reset Filter
                </a>
            @else
                <a href="{{ route('certificates.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-500 to-indigo-600 text-white font-medium px-6 py-3 rounded-xl text-sm transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/30 hover:-translate-y-0.5">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Upload Dokumen Pertama
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function applyCategoryFilter() {
        const checkboxes = document.querySelectorAll('.category-filter-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        document.getElementById('categories-input').value = ids.join(',');
        document.getElementById('categories-modal').classList.add('hidden');
        // Submit the form
        const form = document.querySelector('form');
        form.submit();
    }

    function resetCategoryFilter() {
        document.querySelectorAll('.category-filter-checkbox').forEach(cb => cb.checked = false);
    }
</script>
@endsection
