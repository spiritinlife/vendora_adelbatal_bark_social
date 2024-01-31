<?php

namespace App\Http\Controllers;

use App\Models\Bark;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::orderBy('id', 'asc')->get()
        ]);
    }

    public function show(Request $request, $id): View
    {
        $user = \App\Models\User::find($id);
        $feedType = $request->input('feed', 'home');
        $feed = $this->getBarks($feedType, $id);

        return view('users.show', [
            'user' => $user,
            'feedType' => $feedType,
            'feed' => $feed
        ]);
    }

    public function loadBarks(Request $request, $id): string
    {
        $feedType = $request->input('feed', 'home');
        $feed = $this->getBarks($feedType, $id);

        return view('partials.barks', [
            'feedType' => $feedType,
            'feed' => $feed
        ])->render();
    }

    public function getBarks(string $feedType, string $userId): LengthAwarePaginator
    {
        $user = User::find($userId);
        $page = request('page', 1);
        if ($feedType == 'home') {
            $cacheKey = "user_{$userId}_home_feed_page_{$page}";
            // Cache the feed to avoid unnecessary database queries
            $feed = Cache::remember($cacheKey, $minutes = 60, function () use ($user) {

                return $user
                    ->barks()
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            });
        } else {
            $cacheKey = "user_{$userId}_feed_page_{$page}";
            $feed = Cache::remember($cacheKey, $minutes = 60, function () use ($user) {
                $friendIds = $user->friends()->pluck('friends.friend_id');

                return Bark::whereIn('user_id', $friendIds)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            });
        }
        return $feed;
    }
}
