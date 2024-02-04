<?php

namespace App\Services;

use App\Models\Bark;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserService
{
    public const FEED_TYPE_HOME = 'home';
    public const FEED_TYPE_FRIENDS = 'friends';

    public function getUsers(): Collection
    {
        return User::orderBy('id', 'asc')->get();
    }

    public function getUser(string $id): User
    {
        return User::find($id);
    }

    public function getBarks($userId, $feedType): LengthAwarePaginator
    {
        $user = $this->getUser($userId);
        if (!$user) {
            return new LengthAwarePaginator([], 0, 10);
        }
        $page = request('page', 1);

        return $this->getCachedBarks($user, $feedType, $page);
    }

    private function getCachedBarks($user, $feedType, $page): ?LengthAwarePaginator
    {
        if (!in_array($feedType, [self::FEED_TYPE_HOME, self::FEED_TYPE_FRIENDS])) {
            return null;
        }
        $userId = $user->id;
        $cacheKey = $feedType === self::FEED_TYPE_FRIENDS
            ? "user_{$userId}_friends_feed_page_{$page}"
            : "user_{$userId}_home_feed_page_{$page}";

        try {
            return Cache::remember($cacheKey, 3600, function () use ($user, $feedType) {
               return $this->getPaginatedBarks($user, $feedType);
            });
        } catch (\Exception $e) {
            Log::error("Error accessing cache for key {$cacheKey}: " . $e->getMessage());
            return $this->getPaginatedBarks($user, $feedType);
        }
    }

    public function getFriendsPaginatedBarks($user): LengthAwarePaginator
    {
        $friendIds = $user->friends()->pluck('friends.friend_id');

        return Bark::whereIn('user_id', $friendIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getOwnPaginatedBarks($user): LengthAwarePaginator
    {
        return $user
            ->barks()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    private function getPaginatedBarks($user, $feedType): LengthAwarePaginator
    {
        return $feedType === self::FEED_TYPE_FRIENDS
            ? $this->getFriendsPaginatedBarks($user)
            : $this->getOwnPaginatedBarks($user);
    }
}
