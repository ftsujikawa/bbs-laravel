@extends('layouts.app')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-lg sm:text-xl font-semibold">新規投稿</h2>
        <a href="{{ route('posts.index') }}" class="text-xs text-slate-300 hover:text-slate-100">一覧に戻る</a>
    </div>

    @if ($errors->any())
        <ul class="mb-3 list-disc space-y-1 pl-5 text-xs sm:text-sm text-red-300">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data" class="space-y-4 rounded-2xl border border-white/5 bg-slate-900/70 p-4 sm:p-6">
        @csrf
        <div>
            <label class="block text-xs sm:text-sm mb-1">タイトル</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
        </div>
        <div>
            <label class="block text-xs sm:text-sm mb-1">ニックネーム</label>
            <input type="text" name="nickname" value="{{ old('nickname') }}" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">
        </div>
        <div>
            <label class="block text-xs sm:text-sm mb-1">本文</label>
            <textarea name="body" rows="5" class="w-full rounded-lg border border-slate-600 bg-slate-950/60 px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/70">{{ old('body') }}</textarea>
        </div>
        <div>
            <label class="block text-xs sm:text-sm mb-1">画像</label>
            <input type="file" name="image" accept="image/*" class="block w-full text-xs sm:text-sm text-slate-200 file:mr-3 file:rounded-full file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-xs file:font-medium hover:file:bg-slate-700">
        </div>
        <div>
            <label class="block text-xs sm:text-sm mb-1">添付ファイル（最大10件まで）</label>
            <div id="post-attachments-wrapper" class="space-y-1"></div>
            <button id="post-attachments-add" type="button" class="mt-1 rounded-full bg-slate-800 px-3 py-1 text-[11px] text-slate-100 hover:bg-slate-700">＋ 添付を追加</button>
            <p class="mt-1 text-[11px] text-slate-400">1行につき1ファイルを選択できます。画像・PDF・ZIP など任意のファイルを最大10件まで添付できます（各 4MB まで）。</p>
        </div>
        <button type="submit" class="inline-flex items-center rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium hover:bg-indigo-400">投稿する</button>
    </form>
@endsection
