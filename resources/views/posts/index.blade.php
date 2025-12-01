@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold">投稿一覧</h1>
            <p class="text-xs sm:text-sm text-slate-300">最新の投稿をチェックしたり、キーワードで検索できます。</p>
            @if (auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center mt-2 rounded-full bg-slate-800 px-3 py-1 text-[11px] text-slate-100 hover:bg-slate-700">
                    管理画面へ
                </a>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('posts.index') }}" method="get" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="キーワード検索"
                    class="w-40 sm:w-56 rounded-full border border-slate-600 bg-slate-900/60 px-3 py-1.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70 placeholder:text-slate-500"
                >
                <button class="rounded-full bg-indigo-500 px-3 py-1.5 text-xs sm:text-sm font-medium hover:bg-indigo-400">
                    検索
                </button>
            </form>

            @auth
                <a href="{{ route('posts.create') }}" class="hidden sm:inline-flex items-center rounded-full border border-indigo-400/70 px-3 py-1.5 text-xs sm:text-sm font-medium text-indigo-100 hover:bg-indigo-500/20">
                    新規投稿
                </a>
            @endauth
        </div>
    </div>

    @auth
        <a href="{{ route('posts.create') }}" class="inline-flex sm:hidden mb-4 items-center rounded-full border border-indigo-400/70 px-3 py-1.5 text-xs font-medium text-indigo-100 hover:bg-indigo-500/20">
            新規投稿
        </a>
    @else
        <p class="mb-4 text-xs sm:text-sm text-slate-300">
            <a href="{{ route('login') }}" class="text-indigo-300 underline">ログイン</a>すると投稿できます。
        </p>
    @endauth

    <div class="space-y-3">
        @forelse ($posts as $post)
            <article class="flex items-stretch gap-3 rounded-xl border border-white/5 bg-slate-900/60 p-3 sm:p-4 shadow-sm hover:border-indigo-500/40 transition-colors">
                @if ($post->image_path)
                    <div class="shrink-0 self-stretch w-36 sm:w-48 min-h-24 sm:min-h-32">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="" class="h-full w-full rounded-lg object-cover">
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h2 class="text-sm sm:text-base font-semibold line-clamp-2">{{ $post->title }}</h2>
                            <div class="mt-0.5 flex items-center gap-1.5 text-xs text-slate-300">
                                @if ($post->user && $post->user->avatar_path)
                                    <img src="{{ asset('storage/' . $post->user->avatar_path) }}" alt="avatar" class="h-5 w-5 rounded-full object-cover">
                                @elseif ($post->user)
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-700 text-[9px] font-semibold">
                                        {{ mb_substr($post->user->name, 0, 1) }}
                                    </span>
                                @endif
                                <span>{{ $post->nickname ?? ($post->user->name ?? '名無し') }}</span>
                            </div>
                        </div>
                        <span class="rounded-full bg-slate-800 px-2 py-0.5 text-[10px] sm:text-xs text-slate-300 whitespace-nowrap">{{ $post->created_at }}</span>
                    </div>

                    <p class="mt-2 text-xs sm:text-sm text-slate-200 line-clamp-3">{!! nl2br(e(\Illuminate\Support\Str::limit($post->body, 200))) !!}</p>

                    <div class="mt-2 flex flex-wrap items-center gap-3 text-[11px] sm:text-xs text-slate-300">
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-800/80 px-2 py-0.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            返信 {{ $post->replies_count }} 件
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-800/80 px-2 py-0.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                            最終返信: {{ $post->last_reply_at ?? 'なし' }}
                        </span>
                        @if ($post->attachments_count ?? 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-800/80 px-2 py-0.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                                添付ファイル {{ $post->attachments_count }}件
                            </span>
                        @endif
                        <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center gap-1 text-indigo-300 hover:text-indigo-200 ml-auto">
                            詳細・返信を見る
                            <span class="text-xs">→</span>
                        </a>

                        @if (auth()->check() && $post->user_id === auth()->id())
                            <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center rounded-full bg-slate-800 px-2 py-1 hover:bg-slate-700">編集</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-full bg-red-600/80 px-2 py-1 hover:bg-red-500">削除</button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <p class="text-sm text-slate-300">まだ投稿はありません。</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->appends(['q' => request('q')])->links() }}
    </div>
@endsection
