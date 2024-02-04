<?php

namespace App\Jobs;

use App\Models\Bark;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBarkCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Bark $bark;
    protected User $user;

    public function __construct($bark, $user)
    {
        $this->bark = $bark;
        $this->user = $user;
    }

    public function handle(): void
    {
        Mail::to($this->user)->send(new \App\Mail\BarkCreated($this->bark));
    }
}
