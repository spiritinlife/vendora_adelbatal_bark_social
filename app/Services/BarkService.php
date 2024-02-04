<?php

namespace App\Services;

use App\Jobs\SendBarkCreatedEmailJob;
use App\Models\Bark;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Jobs\ForgetBarksCacheJob;
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

    private function sendBarkCreatedEmail($bark, $user): void
    {
        SendBarkCreatedEmailJob::dispatch($bark, $user)->onQueue('emails');
    }

    private function forgetBarksCache($userId): void
    {
        ForgetBarksCacheJob::dispatch($userId)->onQueue('cache');
    }
}
