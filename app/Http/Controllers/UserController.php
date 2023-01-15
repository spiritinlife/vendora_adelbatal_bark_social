<?php

namespace App\Http\Controllers;

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
            $feed = $user->barks;
        } else {
            $friends = $user->friends;
            $feed = [];
            foreach ($friends as $friend) {
               foreach ($friend->barks as $bark) {
                    $feed []= $bark;
               }
            }
        }

        $feed = collect($feed)->sortBy([['created_at', 'desc']]);

        return view('users.show', [
            'user' => \App\Models\User::find($id),
            'feedType' => $feedType,
            'feed' => $feed
        ]);
    }
}
