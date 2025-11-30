<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'admins_count' => User::where('is_admin', true)->count(),
            'posts_count' => Post::count(),
            'replies_count' => Reply::count(),
        ];

        $latestPosts = Post::with('user')->latest()->limit(5)->get();

        $dailyPosts = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'latestPosts', 'dailyPosts'));
    }
}
