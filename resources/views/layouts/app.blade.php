<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>掲示板</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: radial-gradient(circle at top, #1f2937 0, #020617 45%, #020617 100%); }
    </style>
</head>
<body>
<div class="min-h-screen text-slate-100">
    <header class="border-b border-white/10 bg-black/40 backdrop-blur">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('posts.index') }}" class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-500 text-sm font-bold">B</span>
                <span class="text-lg font-semibold tracking-tight">掲示板</span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                @auth
                    <div class="flex items-center gap-2">
                        @if (auth()->user()->avatar_path)
                            <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="avatar" class="h-7 w-7 rounded-full object-cover">
                        @else
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-700 text-[11px] font-semibold">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </span>
                        @endif
                        <span class="text-slate-300 text-xs sm:text-sm">{{ auth()->user()->name }} さん</span>
                    </div>
                    <form action="{{ route('logout') }}" method="post" class="hidden sm:block">
                        @csrf
                        <button class="px-3 py-1.5 rounded-full bg-slate-800 hover:bg-slate-700 text-xs font-medium">ログアウト</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-full bg-slate-800 hover:bg-slate-700 text-xs font-medium">ログイン</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline px-3 py-1.5 rounded-full border border-slate-600 hover:border-indigo-400 text-xs font-medium">新規登録</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6 sm:py-10">
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>
        <script src="{{ asset('js/attachments.js') }}"></script>
    </body>
</html>
