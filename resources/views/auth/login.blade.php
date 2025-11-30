@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="mb-5 text-lg sm:text-xl font-semibold text-center">ログイン</h1>

        @if (session('status'))
            <div class="mb-3 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-2 text-xs sm:text-sm text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <ul class="mb-3 list-disc space-y-1 pl-5 text-xs sm:text-sm text-red-300">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4 rounded-2xl border border-white/5 bg-slate-900/70 p-4 sm:p-6">
            @csrf

            <div>
                <label for="email" class="block text-xs sm:text-sm mb-1">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                @error('email')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs sm:text-sm mb-1">パスワード</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                @error('password')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-[11px] sm:text-xs">
                <label for="remember_me" class="inline-flex items-center gap-1 text-slate-300">
                    <input id="remember_me" type="checkbox" name="remember" class="h-3 w-3 rounded border-slate-500 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                    <span>ログイン状態を保持する</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-slate-300 hover:text-slate-100 underline">
                        パスワードをお忘れですか？
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-between pt-1 text-xs sm:text-sm">
                <a href="{{ route('register') }}" class="text-slate-300 hover:text-slate-100 underline">
                    新規登録はこちら
                </a>
                <button type="submit" class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium hover:bg-indigo-400">
                    ログイン
                </button>
            </div>
        </form>
    </div>
@endsection
