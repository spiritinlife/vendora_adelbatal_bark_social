<?php

namespace App\Services;

use App\Models\Bark;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class BarkService
{
    public function createBark($userId, $message): Bark
    {
        $bark = new Bark();
        $user = User::find($userId);

        $bark->message = $message;
        $bark->user_id = $userId;
        $bark->save();

        // Forget cached home feed of each friend
        $this->forgetCachedFriendsFeed($userId);
        // Forget cached home feed of the user (barker)
        $this->forgetCachedHomeFeed($userId);
        $this->sendBarkCreatedEmail($bark, $user);

        return $bark;
    }

    private function forgetCachedHomeFeed($userId): void
    {
        Cache::forget("user_{$userId}_home_feed_page_1");
    }

    private function forgetCachedFriendsFeed($userId): void
    {
        $user = User::find($userId);
        $user->friends->each(function ($friend) {
            Cache::forget("user_{$friend->id}_friends_feed_page_1");
        });
    }

    private function sendBarkCreatedEmail($bark, $user): void
    {
        Mail::to($user)->send(new \App\Mail\BarkCreated($bark));
    }
}
