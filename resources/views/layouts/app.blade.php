<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="description" content="Certificate Wallet - Simpan dan kelola sertifikat digital Anda dengan aman">

    <title>@yield('title', 'Certificate Wallet')</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased">
    @auth
        <!-- Mobile Navigation Bar (Bottom) -->
        <nav class="fixed bottom-0 left-0 right-0 z-50 bg-slate-900/80 backdrop-blur-xl border-t border-slate-800/50 safe-area-bottom lg:hidden" id="mobile-nav">
            <div class="flex items-center justify-around px-2 py-2">
                <a href="{{ route('dashboard') }}" class="nav-item group flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-violet-400 bg-violet-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-[10px] font-medium">Dashboard</span>
                </a>
                <a href="{{ route('certificates.index') }}" class="nav-item group flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs('certificates.*') ? 'text-violet-400 bg-violet-500/10' : 'text-slate-400 hover:text-slate-200' }}">
                    <i data-lucide="folder-open" class="w-5 h-5"></i>
                    <span class="text-[10px] font-medium">Dokumen</span>
                </a>
                <a href="{{ route('certificates.create') }}" class="nav-item group flex flex-col items-center justify-center w-14 h-14 -mt-6 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-2xl shadow-lg shadow-violet-500/30 text-white transition-all duration-300 hover:shadow-violet-500/50 hover:scale-105 active:scale-95">
                    <i data-lucide="plus" class="w-6 h-6"></i>
                </a>
                <button onclick="document.getElementById('search-modal').classList.remove('hidden')" class="nav-item group flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-300 text-slate-400 hover:text-slate-200">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    <span class="text-[10px] font-medium">Cari</span>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="nav-item group flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-300 text-slate-400 hover:text-red-400">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span class="text-[10px] font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:w-64 bg-slate-900/50 backdrop-blur-xl border-r border-slate-800/50">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800/50">
                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white">Certificate Wallet</h1>
                    <p class="text-[10px] text-slate-500">Dokumen Digital Aman</p>
                </div>
            </div>

            <!-- Nav Items -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-violet-300 bg-violet-500/10 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>
                <a href="{{ route('certificates.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('certificates.index') ? 'text-violet-300 bg-violet-500/10 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <i data-lucide="folder-open" class="w-5 h-5"></i>
                    Semua Dokumen
                </a>
                <a href="{{ route('certificates.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('certificates.create') ? 'text-violet-300 bg-violet-500/10 shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <i data-lucide="upload" class="w-5 h-5"></i>
                    Upload Dokumen
                </a>
            </nav>

            <!-- User Info -->
            <div class="px-3 py-4 border-t border-slate-800/50">
                <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-sm font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800/50 transition-all duration-200" title="Logout">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Search Modal -->
        <div id="search-modal" class="hidden fixed inset-0 z-[60] bg-slate-950/80 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
            <div class="flex items-start justify-center pt-20 px-4">
                <div class="w-full max-w-lg bg-slate-900 rounded-2xl border border-slate-800/50 shadow-2xl overflow-hidden">
                    <form action="{{ route('certificates.index') }}" method="GET" class="flex items-center gap-3 px-4 py-4">
                        <i data-lucide="search" class="w-5 h-5 text-slate-400 shrink-0"></i>
                        <input type="text" name="search" placeholder="Cari sertifikat, penerbit, catatan..." class="flex-1 bg-transparent text-slate-100 placeholder-slate-500 text-sm outline-none" autofocus>
                        <button type="button" onclick="document.getElementById('search-modal').classList.add('hidden')" class="text-slate-500 hover:text-slate-300 text-xs">ESC</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="lg:ml-64 pb-24 lg:pb-8">
            <!-- Top Header (Mobile) -->
            <header class="lg:hidden sticky top-0 z-40 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/30">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                            <i data-lucide="wallet" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-sm font-bold text-white">Certificate Wallet</h1>
                            <p class="text-[10px] text-slate-500">@yield('page-subtitle', 'Dokumen Digital Aman')</p>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-xs font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-4 mt-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-sm flex items-center gap-2" id="flash-success">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                    {{ session('success') }}
                    <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-500/50 hover:text-emerald-400">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mx-4 mt-4 px-4 py-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span class="font-medium">Terdapat kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs text-red-300/80">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Page Content -->
            <div class="px-4 py-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    @else
        @yield('content')
    @endauth

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Auto-dismiss flash messages
        setTimeout(() => {
            const flash = document.getElementById('flash-success');
            if (flash) {
                flash.style.transition = 'opacity 0.5s ease';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 4000);

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function(error) {
                    console.log('Service Worker registration failed:', error);
                });
        }

        // Show Install Prompt for PWA
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        function showInstallPrompt() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
