<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): View
    {
        return view('users.index', [
            'users' => $this->userService->getUsers()
        ]);
    }

    public function show(Request $request, $userId): View
    {
        $feedType = $request->input('feed', UserService::FEED_TYPE_HOME);
        $feed = $this->userService->getBarks($userId, $feedType);
        $user = $this->userService->getUser($userId);

        return view('users.show', [
            'user' => $user,
            'feedType' => $feedType,
            'feed' => $feed
        ]);
    }

    public function loadBarks(Request $request, $userId): string
    {
        $feedType = $request->input('feed', UserService::FEED_TYPE_HOME);
        $feed = $this->userService->getBarks($userId, $feedType);

        return view('partials.barks', [
            'feedType' => $feedType,
            'feed' => $feed
        ])->render();
    }
}
