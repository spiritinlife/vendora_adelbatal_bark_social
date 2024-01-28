<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class BarkController extends Controller
{
    public function store(Request $request, $userId)
    {
        $barkData = $request->message;
        $user = \App\Models\User::find($userId);

        $this->validateBark($request);

        $bark = new \App\Models\Bark();
        $bark->message = $barkData;
        $bark->user_id = $userId;
        $bark->save();
        // Forget cached home feed of each friend
        $user->friends->each(function ($friend) {
            Cache::forget("user_{$friend->id}_friends_feed_page_1");
        });
        // Forget cached home feed of the user (barker)
        Cache::forget("user_{$userId}_home_feed_page_1");
        Mail::to($user)->send(new \App\Mail\BarkCreated($bark));

        return redirect()->back();
    }

    private function validateBark($request): void
    {
        $request->validate([
            'message' => [
                'required',
                'string',
                'max:500',
                'min:4',
                fn ($attribute, $value, $fail) => $this->validateFourLettersBark($attribute, $value, $fail),
            ],
        ], [
            'message.required' => 'A bark message is required.',
            'message.min' => 'The bark message must be at least :min characters.',
            'message.max' => 'A bark cannot be more than 500 characters.',
        ]);

    }

    private function validateFourLettersBark($attribute, $value, $fail): void
    {
        if (strlen($value) === 4 && $value !== 'Bark') {
            $fail('If the bark message is 4 characters, it can only be the word "Bark".');
        }
    }
}

