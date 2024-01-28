<?php

namespace App\Http\Controllers;

use App\Models\Bark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => \App\Models\User::orderBy('id', 'asc')->get()
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        $feedType = $request->input('feed', 'home');

        if ($feedType == 'home') {
            $cacheKey = "user_{$id}_home_feed";
            // Cache the feed to avoid unnecessary database queries
            $feed = Cache::remember($cacheKey, $minutes = 60, function () use ($user) {
                return $user->barks()->orderBy('created_at', 'desc')->get();
            });
        } else {
            // Unnecessary nested loop, we can use a query instead, avoiding the N+1 problem, and optimize for memory usage
            $cacheKey = "user_{$id}_friends_feed";
            // Cache the feed to avoid unnecessary database queries
            $feed = Cache::remember($cacheKey, $minutes = 60, function () use ($user) {
                $friendIds = $user->friends()->pluck('friends.friend_id');
                return Bark::whereIn('user_id', $friendIds)->orderBy('created_at', 'desc')->get();
            });
//            $friends = $user->friends;
//            $feed = [];
//            foreach ($friends as $friend) {
//                foreach ($friend->barks as $bark) {
//                    $feed []= $bark;
//                }
//            }
        }

//        $feed = collect($feed)->sortBy([['created_at', 'desc']]);

        return view('users.show', [
            'user' => \App\Models\User::find($id),
            'feedType' => $feedType,
            'feed' => $feed
        ]);
    }
}
