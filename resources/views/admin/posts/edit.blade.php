@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 text-slate-100">
    <h1 class="text-2xl font-bold mb-6">投稿編集（管理用）</h1>

    @if ($errors->any())
        <ul class="mb-4 text-sm text-red-300 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm mb-1">タイトル</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border border-slate-600 bg-slate-950/60 rounded px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">本文</label>
            <textarea name="body" rows="6" class="w-full border border-slate-600 bg-slate-950/60 rounded px-2 py-1 text-sm">{{ old('body', $post->body) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-1">ニックネーム</label>
            <input type="text" name="nickname" value="{{ old('nickname', $post->nickname) }}" class="w-full border border-slate-600 bg-slate-950/60 rounded px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">画像</label>
            @if ($post->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="" class="h-32 rounded object-cover border border-slate-700">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="block w-full text-sm text-slate-200 file:mr-3 file:rounded-full file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-medium hover:file:bg-slate-700">
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-1 bg-indigo-600 text-white rounded text-sm">保存</button>
            <a href="{{ route('admin.posts.index') }}" class="px-4 py-1 bg-slate-700 text-white rounded text-sm">一覧に戻る</a>
        </div>
    </form>
</div>
@endsection
