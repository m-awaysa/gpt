<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function login()
    {

        // Attempt to log the user in
        Auth::attempt(['email' => 'd@d.d', 'password' => 'password']);
        // If unsuccessful, redirect back with input (except password)
        return back();
    }

    // public function stream(Request $request)
    // {
    //     event(new MessageSent($request->qr));
    // }
}
