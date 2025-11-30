@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 text-slate-100">
    <div class="mb-4 flex items-center justify-between gap-2">
        <h1 class="text-2xl font-bold">投稿管理</h1>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-full bg-slate-800 px-3 py-1 text-[11px] text-slate-100 hover:bg-slate-700">
            ダッシュボードに戻る
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.posts.index') }}" class="mb-4 flex gap-2">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="タイトル・本文・ニックネームで検索"
            class="border border-slate-600 bg-slate-950/60 rounded px-2 py-1 flex-1 text-sm"
        >
        <button class="px-4 py-1 bg-indigo-600 text-white rounded text-sm">検索</button>
    </form>

    <div class="rounded-xl bg-slate-900/80 border border-white/10 p-4">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left py-2">ID</th>
                    <th class="text-left py-2">タイトル</th>
                    <th class="text-left py-2">投稿者</th>
                    <th class="text-left py-2">返信数</th>
                    <th class="text-left py-2">表示</th>
                    <th class="text-left py-2">作成日時</th>
                    <th class="text-left py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr class="border-b">
                        <td class="py-2">{{ $post->id }}</td>
                        <td class="py-2">
                            <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="py-2">{{ $post->user->name ?? $post->nickname ?? '-' }}</td>
                        <td class="py-2">{{ $post->replies_count }}</td>
                        <td class="py-2">{{ $post->is_hidden ? '非表示' : '表示中' }}</td>
                        <td class="py-2">{{ $post->created_at }}</td>
                        <td class="py-2 flex gap-1">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="px-3 py-1 bg-gray-700 text-white rounded text-xs">編集</a>
                            <form method="POST" action="{{ route('admin.posts.toggle-hidden', $post) }}" onsubmit="return confirm('この投稿の表示状態を切り替えますか？');">
                                @csrf
                                @method('PATCH')
                                <button class="px-3 py-1 bg-yellow-600 text-white rounded text-xs">
                                    {{ $post->is_hidden ? '表示にする' : '非表示にする' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('この投稿を削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">投稿がありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
