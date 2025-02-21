<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function isUserAuthenticated()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user()
            ]);
        }

        return response()->json(['authenticated' => false], 200);
    }
}
