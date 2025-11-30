@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto">
        <h1 class="mb-5 text-lg sm:text-xl font-semibold text-center">ユーザー登録</h1>

        @if ($errors->any())
            <ul class="mb-3 list-disc space-y-1 pl-5 text-xs sm:text-sm text-red-300">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4 rounded-2xl border border-white/5 bg-slate-900/70 p-4 sm:p-6">
            @csrf

            <div>
                <label for="name" class="block text-xs sm:text-sm mb-1">名前</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                @error('name')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs sm:text-sm mb-1">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                @error('email')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs sm:text-sm mb-1">パスワード</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                @error('password')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs sm:text-sm mb-1">パスワード（確認）</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                @error('password_confirmation')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="avatar" class="block text-xs sm:text-sm mb-1">アバター画像（任意）</label>
                <input id="avatar" type="file" name="avatar" accept="image/*" class="block w-full text-xs sm:text-sm text-slate-200 file:mr-3 file:rounded-full file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-medium hover:file:bg-slate-700">
                @error('avatar')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-1 text-xs sm:text-sm">
                <a href="{{ route('login') }}" class="text-slate-300 hover:text-slate-100 underline">
                    すでに登録済みの方はこちら
                </a>
                <button type="submit" class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium hover:bg-indigo-400">
                    登録する
                </button>
            </div>
        </form>
    </div>
@endsection
