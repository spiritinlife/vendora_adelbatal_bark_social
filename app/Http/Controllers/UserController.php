<?php

namespace App\Http\Controllers;

use App\Models\Bark;
use Illuminate\Http\Request;

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
            $feed = $user->barks()->orderBy('created_at', 'asc')->get();
        } else {
            // Unnecessary nested loop, we can use a query instead, avoiding the N+1 problem, and optimize for memory usage
            $friendIds = $user->friends()->pluck('friends.friend_id');
            $feed = Bark::whereIn('user_id', $friendIds)
                ->orderBy('created_at', 'desc')
                ->get();
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
