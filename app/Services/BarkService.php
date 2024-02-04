<?php

namespace App\Services;

use App\Jobs\SendBarkCreatedEmailJob;
use App\Models\Bark;
use App\Models\User;
use App\Jobs\ForgetBarksCacheJob;
class BarkService
{
    /**
     * @throws \Exception
     */
    public function createBark($userId, $message): Bark
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $bark = Bark::create([
            'message' => $message,
            'user_id' => $userId
        ]);
        if (!$bark) {
            throw new \Exception('Bark not created');
        }

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
