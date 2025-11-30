<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('is_admin')->orderBy('id')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleAdmin(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('status', '自分自身の管理者権限は変更できません。');
        }

        $user->is_admin = ! (bool) ($user->is_admin ?? false);
        $user->save();

        return redirect()->back()->with('status', '管理者権限を更新しました。');
    }
}
