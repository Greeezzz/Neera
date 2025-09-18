<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FollowController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        $auth = Auth::user();
        if ($auth->id === $user->id) return back();
        $auth->follow($user->id);
        return back();
    }

    public function destroy(User $user): RedirectResponse
    {
        $auth = Auth::user();
        if ($auth->id === $user->id) return back();
        $auth->unfollow($user->id);
        return back();
    }
}
