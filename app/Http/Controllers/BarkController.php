<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BarkController extends Controller
{
    public function store(Request $request, $userId)
    {
        $barks = $request->message;
        $user = \App\Models\User::find($userId);

        if (!$this->validateBark($barks)) {
            return redirect()->back()->with('error', 'Input is invalid: ' . $barks);
        }

        $bark = new \App\Models\Bark;
        $bark->message = $barks;
        $bark->user_id = $userId;
        $bark->save();

        Mail::to($user)->send(new \App\Mail\BarkCreated($bark));

        return redirect()->back();
    }

    function validateBark($sentence) {
        if (strlen($sentence) > 500) {
            return false;
        }

        // dd(str_ends_with($sentence, "Bark"));
        if (strlen($sentence) < 5 && !str_ends_with($sentence, "Bark")) {
            return false;
        }

        return true;
    }
}
