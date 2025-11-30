<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reply;
use App\Models\PostAttachment;
use App\Models\ReplyAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = Post::with(['user'])->withCount(['replies', 'attachments'])
            ->withMax('replies as last_reply_at', 'created_at')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhere('nickname', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('posts.index', compact('posts', 'search'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'nickname' => ['nullable', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'max:4096'],
        ]);

        $data = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => auth()->id(),
            'nickname' => $validated['nickname'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = $path;
        }

        $post = Post::create($data);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('posts', 'public');
                $post->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('posts.index')
            ->with('status', '投稿しました。');
    }

    public function show(Post $post)
    {
        $post->load('user');
        $replies = $post->replies()->with('user')->latest()->paginate(10);

        return view('posts.show', compact('post', 'replies'));
    }

    public function replyStore(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => ['required'],
            'nickname' => ['nullable', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'max:2048'],
        ]);

        $data = [
            'body' => $validated['body'],
            'nickname' => $validated['nickname'] ?? null,
            'user_id' => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('replies', 'public');
            $data['image_path'] = $path;
        }

        $reply = $post->replies()->create($data);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('replies', 'public');
                $reply->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('posts.show', $post)
            ->with('status', '返信を投稿しました。');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'nickname' => ['nullable', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['image', 'max:4096'],
        ]);

        $data = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'nickname' => $validated['nickname'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $path = $request->file('image')->store('posts', 'public');
            $data['image_path'] = $path;
        }

        $post->update($data);

        if ($request->hasFile('attachments')) {
            foreach ($post->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
            }
            $post->attachments()->delete();

            foreach ($request->file('attachments') as $file) {
                $path = $file->store('posts', 'public');
                $post->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('posts.show', $post)
            ->with('status', '投稿を更新しました。');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        foreach ($post->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        $post->attachments()->delete();

        $post->delete();

        return redirect()->route('posts.index')
            ->with('status', '投稿を削除しました。');
    }
}
