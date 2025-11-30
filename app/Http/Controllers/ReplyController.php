<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\ReplyAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReplyController extends Controller
{
    public function edit(Reply $reply)
    {
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }

        return view('replies.edit', compact('reply'));
    }

    public function update(Request $request, Reply $reply)
    {
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }

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
        ];

        if ($request->hasFile('image')) {
            if ($reply->image_path) {
                Storage::disk('public')->delete($reply->image_path);
            }
            $path = $request->file('image')->store('replies', 'public');
            $data['image_path'] = $path;
        }

        $reply->update($data);

        if ($request->hasFile('attachments')) {
            foreach ($reply->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
            }
            $reply->attachments()->delete();

            foreach ($request->file('attachments') as $file) {
                $path = $file->store('replies', 'public');
                $reply->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('posts.show', $reply->post_id)
            ->with('status', '返信を更新しました。');
    }

    public function destroy(Reply $reply)
    {
        if ($reply->user_id !== auth()->id()) {
            abort(403);
        }

        $postId = $reply->post_id;
        if ($reply->image_path) {
            Storage::disk('public')->delete($reply->image_path);
        }

        foreach ($reply->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        $reply->attachments()->delete();

        $reply->delete();

        return redirect()->route('posts.show', $postId)
            ->with('status', '返信を削除しました。');
    }
}
