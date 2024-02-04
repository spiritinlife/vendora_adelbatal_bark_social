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
        $user = User::find($userId);

        $bark = Bark::create([
            'message' => $message,
            'user_id' => $userId
        ]);

        $bark->save();

        $this->forgetBarksCache($userId);
        $this->sendBarkCreatedEmail($bark, $user);

        return $bark;
    }

    private function sendBarkCreatedEmail($bark, $user): void
    {
        SendBarkCreatedEmailJob::dispatch($bark, $user)->onQueue('emails');
    }

    private function forgetBarksCache($userId): void
    {
        ForgetBarksCacheJob::dispatch($userId)->onQueue('forget-cache');
    }
}
