<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Support\ApiResponse;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
        $this->middleware('auth:sanctum');
    }

    public function show(Request $request)
    {
        $user = $request->user()->load(['companies.activePlanOne.plan']);
        
        // Get only the first company with its plan
        $user->company = $user->companies->first();
        unset($user->companies);
        
        return response()->json([
            'user' => $user
        ]);

        

    } 

    public function update(UpdateProfileRequest $request)
    {
        $user = $this->users->updateProfile($request->user(), $request->validated());
        return ApiResponse::success($user);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return ApiResponse::error('Current password is incorrect', 422);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return ApiResponse::success(['message' => 'Password updated successfully']);
    }
}
