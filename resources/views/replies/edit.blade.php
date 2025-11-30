@extends('layouts.app')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-lg sm:text-xl font-semibold">返信を編集</h2>
        <a href="{{ route('posts.show', $reply->post_id) }}" class="text-xs text-slate-300 hover:text-slate-100">投稿詳細に戻る</a>
    </div>

    @if ($errors->any())
        <ul class="mb-3 list-disc space-y-1 pl-5 text-xs sm:text-sm text-red-300">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('replies.update', $reply) }}" method="post" enctype="multipart/form-data" class="space-y-4 rounded-2xl border border-white/5 bg-slate-900/70 p-4 sm:p-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs sm:text-sm mb-1">ニックネーム</label>
            <input type="text" name="nickname" value="{{ old('nickname', $reply->nickname) }}" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
        </div>

        <div>
            <label class="block text-xs sm:text-sm mb-1">本文</label>
            <textarea name="body" rows="4" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">{{ old('body', $reply->body) }}</textarea>
        </div>

        <div>
            <label class="block text-xs sm:text-sm mb-1">画像</label>
            @if ($reply->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $reply->image_path) }}" alt="image" class="h-14 w-14 rounded-lg object-cover">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="block w-full text-xs sm:text-sm text-slate-200 file:mr-3 file:rounded-full file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-medium hover:file:bg-slate-700">
        </div>

        <button type="submit" class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium hover:bg-indigo-400">更新する</button>
    </form>
@endsection
