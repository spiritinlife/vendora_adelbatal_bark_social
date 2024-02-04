<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarkRequest;
use App\Services\BarkService;
use Illuminate\Http\RedirectResponse;

class BarkController extends Controller
{
    protected BarkService $barkService;

    public function __construct(BarkService $barkService)
    {
        $this->barkService = $barkService;
    }

    public function store(BarkRequest $request, $userId): RedirectResponse
    {
        try {
            $this->barkService->createBark($userId, $request->message);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
