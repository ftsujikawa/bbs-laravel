@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between gap-3">
        <a href="{{ route('posts.index') }}" class="inline-flex items-center text-xs text-slate-300 hover:text-slate-100">
            ← 一覧に戻る
        </a>
        <span class="rounded-full bg-slate-800 px-2 py-1 text-[11px] text-slate-200">投稿日時: {{ $post->created_at }}</span>
    </div>

    @if (auth()->check() && $post->user_id === auth()->id())
        <article class="mb-8 flex gap-4 rounded-2xl border border-white/5 bg-slate-900/70 p-4 sm:p-6 shadow cursor-pointer" onclick="window.location='{{ route('posts.edit', $post) }}'">
    @else
        <article class="mb-8 flex gap-4 rounded-2xl border border-white/5 bg-slate-900/70 p-4 sm:p-6 shadow">
    @endif
        @if ($post->image_path)
            <div class="shrink-0">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="image" class="max-w-full h-auto max-h-96 rounded-xl object-cover">
            </div>
        @endif
        <div class="flex-1 min-w-0 space-y-2">
            <h2 class="text-lg sm:text-xl font-semibold">{{ $post->title }}</h2>
            <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-300">
                @if ($post->user && $post->user->avatar_path)
                    <img src="{{ asset('storage/' . $post->user->avatar_path) }}" alt="avatar" class="h-6 w-6 rounded-full object-cover">
                @elseif ($post->user)
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-700 text-[10px] font-semibold">
                        {{ mb_substr($post->user->name, 0, 1) }}
                    </span>
                @endif
                <span>投稿者: {{ $post->nickname ?? ($post->user->name ?? '名無し') }}</span>
            </div>
            <p class="mt-2 whitespace-pre-wrap text-sm sm:text-base leading-relaxed">{!! nl2br(e($post->body)) !!}</p>
            @if ($post->attachments && $post->attachments->count())
                <p class="mt-2 text-xs sm:text-sm text-slate-300">添付ファイル {{ $post->attachments->count() }}件</p>
                <ul class="mt-1 space-y-1 text-xs sm:text-sm text-slate-200">
                    @foreach ($post->attachments as $attachment)
                        <li>
                            <a
                                href="{{ asset('storage/' . $attachment->path) }}"
                                download="{{ $attachment->original_name ?? basename($attachment->path) }}"
                                class="underline hover:text-indigo-300"
                            >
                                {{ $attachment->original_name ?? basename($attachment->path) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
            @if (auth()->check() && $post->user_id === auth()->id())
                <div class="mt-2 flex gap-2 text-[11px] sm:text-xs">
                    <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center rounded-full bg-slate-800 px-2 py-1 hover:bg-slate-700">編集</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center rounded-full bg-red-600/80 px-2 py-1 hover:bg-red-500">削除</button>
                    </form>
                </div>
            @endif
        </div>
    </article>

    <section class="mb-10">
        <h3 class="mb-3 text-sm sm:text-base font-semibold">返信一覧</h3>
        <div class="space-y-3">
            @forelse ($replies as $reply)
                <div class="rounded-xl border border-white/5 bg-slate-900/60 p-3 sm:p-4">
                    <div class="flex gap-3">
                        @if ($reply->image_path)
                            <div class="shrink-0">
                                <img src="{{ asset('storage/' . $reply->image_path) }}" alt="image" class="h-12 w-12 sm:h-14 sm:w-14 rounded-lg object-cover">
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        @if ($reply->user && $reply->user->avatar_path)
                                            <img src="{{ asset('storage/' . $reply->user->avatar_path) }}" alt="avatar" class="h-5 w-5 rounded-full object-cover">
                                        @elseif ($reply->user)
                                            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-700 text-[9px] font-semibold">
                                                {{ mb_substr($reply->user->name, 0, 1) }}
                                            </span>
                                        @endif
                                        <p class="text-xs sm:text-sm font-medium">{{ $reply->nickname ?? ($reply->user->name ?? '名無し') }}</p>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-200 whitespace-pre-wrap">{!! nl2br(e($reply->body)) !!}</p>
                                    @if ($reply->attachments && $reply->attachments->count())
                                        <p class="mt-1 text-[11px] text-slate-300">添付ファイル {{ $reply->attachments->count() }}件</p>
                                        <ul class="mt-1 space-y-1 text-[11px] text-slate-200">
                                            @foreach ($reply->attachments as $attachment)
                                                <li>
                                                    <a
                                                        href="{{ asset('storage/' . $attachment->path) }}"
                                                        download="{{ $attachment->original_name ?? basename($attachment->path) }}"
                                                        class="underline hover:text-indigo-300"
                                                    >
                                                        {{ $attachment->original_name ?? basename($attachment->path) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400">{{ $reply->created_at }}</span>
                            </div>

                            @if (auth()->check() && $reply->user_id === auth()->id())
                                <div class="mt-2 flex gap-2 text-[11px] sm:text-xs">
                                    <a href="{{ route('replies.edit', $reply) }}" class="inline-flex items-center rounded-full bg-slate-800 px-2 py-1 hover:bg-slate-700">編集</a>
                                    <form action="{{ route('replies.destroy', $reply) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-full bg-red-600/80 px-2 py-1 hover:bg-red-500">削除</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-300">まだ返信はありません。</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $replies->links() }}
        </div>
    </section>

    <section class="rounded-2xl border border-indigo-500/40 bg-slate-900/70 p-4 sm:p-6">
        <h3 class="mb-3 text-sm sm:text-base font-semibold">返信を書く</h3>

        @if ($errors->any())
            <ul class="mb-3 list-disc space-y-1 pl-5 text-xs sm:text-sm text-red-300">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @auth
            <form action="{{ route('posts.reply', $post) }}" method="post" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs sm:text-sm mb-1">ニックネーム</label>
                    <input type="text" name="nickname" value="{{ old('nickname') }}" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-1.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm mb-1">本文</label>
                    <textarea name="body" rows="4" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">{{ old('body') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm mb-1">画像</label>
                    <input type="file" name="image" accept="image/*" class="block w-full text-xs sm:text-sm text-slate-200 file:mr-3 file:rounded-full file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-medium hover:file:bg-slate-700">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm mb-1">添付ファイル（最大10件まで）</label>
                    <div id="reply-attachments-wrapper" class="space-y-1"></div>
                    <button id="reply-attachments-add" type="button" class="mt-1 rounded-full bg-slate-800 px-3 py-1 text-[11px] text-slate-100 hover:bg-slate-700">＋ 添付を追加</button>
                    <p class="mt-1 text-[11px] text-slate-400">1行につき1ファイルを選択できます。画像・PDF・ZIP など任意のファイルを最大10件まで添付できます（各 2MB まで）。</p>
                </div>
                <button type="submit" class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium hover:bg-indigo-400">返信する</button>
            </form>
        @else
            <p class="text-xs sm:text-sm text-slate-300">返信するには <a href="{{ route('login') }}" class="text-indigo-300 underline">ログイン</a> してください。</p>
        @endauth
    </section>
@endsection
