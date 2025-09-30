<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\SetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Support\ApiResponse;

class InviteAcceptanceController extends Controller
{
    public function show(Request $request)
    {
        // This endpoint can be used by frontend to validate token before showing set-password form
        $token = $request->query('token');
        if (! $token) return ApiResponse::error('Token required', 400);

        $pat = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if (! $pat) return ApiResponse::error('Invalid or expired token', 401);

        $user = $pat->tokenable;
        if (! $user) return ApiResponse::error('Token not linked to user', 401);

        return ApiResponse::success(['user' => $user, 'token' => $token]);
    }

    public function setPassword(SetPasswordRequest $request)
    {
        $token = $request->input('token');
        $pat = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if (! $pat) return ApiResponse::error('Invalid or expired token', 401);

        $user = $pat->tokenable;
        if (! $user) return ApiResponse::error('Token not linked to user', 401);

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Optionally revoke the invite token to make it single-use
        $pat->delete();

        // Create a new personal access token for the user session
        $newToken = $user->createToken('web')->plainTextToken;

        return ApiResponse::success(['token' => $newToken, 'user' => $user]);
    }
}
