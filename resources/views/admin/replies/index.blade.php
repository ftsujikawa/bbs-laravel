@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 text-slate-100">
    <div class="mb-4 flex items-center justify-between gap-2">
        <h1 class="text-2xl font-bold">返信管理</h1>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-full bg-slate-800 px-3 py-1 text-[11px] text-slate-100 hover:bg-slate-700">
            ダッシュボードに戻る
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.replies.index') }}" class="mb-4 flex gap-2">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="本文・ニックネームで検索"
            class="border border-slate-600 bg-slate-950/60 rounded px-2 py-1 flex-1 text-sm"
        >
        <button class="px-4 py-1 bg-indigo-600 text-white rounded text-sm">検索</button>
    </form>

    <div class="rounded-xl bg-slate-900/80 border border-white/10 p-4">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left py-2">ID</th>
                    <th class="text-left py-2">投稿</th>
                    <th class="text-left py-2">ニックネーム</th>
                    <th class="text-left py-2">本文</th>
                    <th class="text-left py-2">作成日時</th>
                    <th class="text-left py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($replies as $reply)
                    <tr class="border-b">
                        <td class="py-2">{{ $reply->id }}</td>
                        <td class="py-2">
                            @if ($reply->post)
                                <a href="{{ route('posts.show', $reply->post) }}" class="text-blue-600 hover:underline">
                                    {{ $reply->post->title }}
                                </a>
                            @else
                                <span class="text-gray-500">(削除済み)</span>
                            @endif
                        </td>
                        <td class="py-2">{{ $reply->nickname ?? ($reply->user->name ?? '名無し') }}</td>
                        <td class="py-2">{{ \Illuminate\Support\Str::limit($reply->body, 50) }}</td>
                        <td class="py-2">{{ $reply->created_at }}</td>
                        <td class="py-2">
                            <form method="POST" action="{{ route('admin.replies.destroy', $reply) }}" onsubmit="return confirm('この返信を削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">返信がありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $replies->links() }}
        </div>
    </div>
</div>
@endsection
