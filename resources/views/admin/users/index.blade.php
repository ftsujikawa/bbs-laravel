@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 text-slate-100">
    <div class="mb-4 flex items-center justify-between gap-2">
        <h1 class="text-2xl font-bold">ユーザー管理</h1>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-full bg-slate-800 px-3 py-1 text-[11px] text-slate-100 hover:bg-slate-700">
            ダッシュボードに戻る
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-500/50 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 flex gap-2">
        <input
            type="text"
            name="q"
            value="{{ $search }}"
            placeholder="名前・メールアドレスで検索"
            class="border border-slate-600 bg-slate-950/60 rounded px-2 py-1 flex-1 text-sm"
        >
        <button class="px-4 py-1 bg-indigo-600 text-white rounded text-sm">検索</button>
    </form>

    <div class="rounded-xl bg-slate-900/80 border border-white/10 p-4">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left py-2">ID</th>
                    <th class="text-left py-2">名前</th>
                    <th class="text-left py-2">メールアドレス</th>
                    <th class="text-left py-2">管理者</th>
                    <th class="text-left py-2">登録日時</th>
                    <th class="text-left py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b">
                        <td class="py-2">{{ $user->id }}</td>
                        <td class="py-2">{{ $user->name }}</td>
                        <td class="py-2">{{ $user->email }}</td>
                        <td class="py-2">{{ $user->is_admin ? 'はい' : 'いいえ' }}</td>
                        <td class="py-2">{{ $user->created_at }}</td>
                        <td class="py-2">
                            @if (auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" onsubmit="return confirm('このユーザーの管理者権限を切り替えますか？');">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1 bg-gray-700 text-white rounded text-xs">
                                        {{ $user->is_admin ? '管理者解除' : '管理者にする' }}
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-500">自分自身は変更できません</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">ユーザーがいません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
