<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class MagicLoginController extends Controller
{
    public function exchange(Request $request)
    {
        $request->validate([
            'token' => ['required','string'],
        ]);

        $token = $request->input('token');

        $pat = PersonalAccessToken::findToken($token);
        if (! $pat) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $user = $pat->tokenable;
        if (! $user) {
            return response()->json(['message' => 'Token does not belong to a user'], 401);
        }

        // Optionally refresh token or return as-is. We'll return token and user for frontend to store.
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
