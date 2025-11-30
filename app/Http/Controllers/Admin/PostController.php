<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = Post::with(['user'])->withCount(['replies', 'attachments'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhere('nickname', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(20)->withQueryString();

        return view('admin.posts.index', compact('posts', 'search'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'nickname' => ['nullable', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
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

        return redirect()->route('admin.posts.index')->with('status', '投稿を更新しました。');
    }

    public function toggleHidden(Post $post)
    {
        $post->is_hidden = ! (bool) ($post->is_hidden ?? false);
        $post->save();

        return redirect()->back()->with('status', '表示状態を更新しました。');
    }

    public function destroy(Post $post)
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        foreach ($post->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        $post->attachments()->delete();

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('status', '投稿を削除しました。');
    }
}
