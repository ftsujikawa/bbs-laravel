<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReplyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = Reply::with(['post', 'user'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhere('nickname', 'like', "%{$search}%");
            });
        }

        $replies = $query->paginate(20)->withQueryString();

        return view('admin.replies.index', compact('replies', 'search'));
    }

    public function destroy(Reply $reply)
    {
        $postId = $reply->post_id;

        if ($reply->image_path) {
            Storage::disk('public')->delete($reply->image_path);
        }

        foreach ($reply->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        $reply->attachments()->delete();

        $reply->delete();

        return redirect()->route('admin.replies.index')
            ->with('status', "返信(ID: {$reply->id}) を削除しました。");
    }
}
