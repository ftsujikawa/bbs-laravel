@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 text-slate-100">
    <h1 class="text-2xl font-bold mb-6">管理者ダッシュボード</h1>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center rounded-full bg-slate-800 px-4 py-1.5 text-xs font-medium text-slate-100 hover:bg-slate-700">
            投稿管理
        </a>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-full bg-slate-800 px-4 py-1.5 text-xs font-medium text-slate-100 hover:bg-slate-700">
            ユーザー管理
        </a>
        <a href="{{ route('admin.replies.index') }}" class="inline-flex items-center rounded-full bg-slate-800 px-4 py-1.5 text-xs font-medium text-slate-100 hover:bg-slate-700">
            返信管理
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-white/10">
            <div class="text-xs text-slate-300">ユーザー数</div>
            <div class="mt-1 text-2xl font-semibold">{{ $stats['users_count'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-white/10">
            <div class="text-xs text-slate-300">管理者数</div>
            <div class="mt-1 text-2xl font-semibold">{{ $stats['admins_count'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-white/10">
            <div class="text-xs text-slate-300">投稿数</div>
            <div class="mt-1 text-2xl font-semibold">{{ $stats['posts_count'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-white/10">
            <div class="text-xs text-slate-300">返信数</div>
            <div class="mt-1 text-2xl font-semibold">{{ $stats['replies_count'] }}</div>
        </div>
    </div>

    <div class="rounded-xl bg-slate-900/80 border border-white/10 p-4 mb-6">
        <h2 class="text-lg font-semibold mb-4">最新の投稿</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">ID</th>
                    <th class="text-left py-2">タイトル</th>
                    <th class="text-left py-2">投稿者</th>
                    <th class="text-left py-2">作成日時</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestPosts as $post)
                    <tr class="border-b">
                        <td class="py-2">{{ $post->id }}</td>
                        <td class="py-2">
                            <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="py-2">{{ $post->user->name ?? $post->nickname ?? '-' }}</td>
                        <td class="py-2">{{ $post->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-400">投稿がありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rounded-xl bg-slate-900/80 border border-white/10 p-4">
        <h2 class="text-lg font-semibold mb-4">日別投稿数（直近7日）</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">日付</th>
                    <th class="text-left py-2">投稿数</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dates = collect(range(0, 6))->map(function ($i) {
                        return \Illuminate\Support\Carbon::now()->subDays(6 - $i)->toDateString();
                    });
                    $dailyMap = $dailyPosts->keyBy('date');
                @endphp
                @foreach ($dates as $date)
                    <tr class="border-b border-white/10">
                        <td class="py-2">{{ $date }}</td>
                        <td class="py-2">{{ $dailyMap[$date]->count ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
