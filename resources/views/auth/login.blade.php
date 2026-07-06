@extends('layouts.app')

@section('title', 'Login - Certificate Wallet')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-slate-950">
    <!-- Background glow effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-sm relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-violet-500/30">
                <i data-lucide="wallet" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Certificate Wallet</h1>
            <p class="text-sm text-slate-500 mt-1">Masuk ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800/50 rounded-2xl p-6 shadow-2xl">
            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-400 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/50 transition-all duration-300"
                            placeholder="email@contoh.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-400 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500/50 transition-all duration-300"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-violet-500 focus:ring-violet-500/50 focus:ring-offset-0">
                        <span class="text-xs text-slate-400">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-violet-500 to-indigo-600 text-white font-medium py-3 rounded-xl text-sm transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/30 hover:-translate-y-0.5 active:translate-y-0 active:shadow-none">
                    Masuk
                </button>
            </form>
        </div>

        @if(config('app.registration_enabled'))
            <p class="text-center text-xs text-slate-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-violet-400 hover:text-violet-300 font-medium transition-colors">Daftar</a>
            </p>
        @endif
    </div>
</div>
@endsection
