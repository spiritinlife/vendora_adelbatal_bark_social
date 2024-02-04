<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ForgetBarksCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $userId;
    private UserService $userService;

    public function __construct($userId, UserService $userService)
    {
        $this->userId = $userId;
        $this->userService = $userService;
    }

    public function handle(): void
    {
        $this->forgetCachedFriendsFeed($this->userId);
        $this->forgetCachedHomeFeed($this->userId);
    }

    private function forgetCachedHomeFeed($userId): void
    {
        $user = User::find($userId);
        $pages = $this->userService->getOwnPaginatedBarks($user)->lastPage();
        collect(range(1, $pages))->each(fn($page) => (
        Cache::forget("user_{$userId}_home_feed_page_{$page}")
        ));
    }

    private function forgetCachedFriendsFeed($userId): void
    {
        $user = User::find($userId);
        $user->friends->each(function ($friend) {
            $pages = $this->userService->getFriendsPaginatedBarks($friend)->lastPage();
            collect(range(1, $pages))->each(fn($page) => (
            Cache::forget("user_{$friend->id}_friends_feed_page_{$page}")
            ));
        });
    }
}
