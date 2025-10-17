<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteSocialOnboardingRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    /**
     * Redirect user to Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            $redirectUrl = Socialite::driver('google')
                ->stateless()
                ->with(['access_type' => 'offline'])
                ->redirect()
                ->getTargetUrl();
            
            return ApiResponse::success(['redirect_url' => $redirectUrl]);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to generate Google redirect URL', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle Google callback and auto-populate onboarding
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // For GET requests (browser redirect), get code from query params
            $code = $request->input('code') ?? $request->query('code');
            
            if (!$code) {
                return ApiResponse::error('Authorization code is required', 422);
            }

            $googleUser = Socialite::driver('google')->stateless()->user();
            $result = $this->users->findOrCreateFromSocial('google', $googleUser);
            
            return ApiResponse::success([
                'token' => $result['token'],
                'user' => $result['user'],
                'is_new_user' => $result['is_new_user']
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('Social authentication failed', 422, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Redirect user to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        try {
            $redirectUrl = Socialite::driver('facebook')
                ->stateless()
                ->redirect()
                ->getTargetUrl();
            
            return ApiResponse::success(['redirect_url' => $redirectUrl]);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to generate Facebook redirect URL', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle Facebook callback and auto-populate onboarding
     */
    public function handleFacebookCallback(Request $request)
    {
        try {
            // For GET requests (browser redirect), get code from query params
            $code = $request->input('code') ?? $request->query('code');
            
            if (!$code) {
                return ApiResponse::error('Authorization code is required', 422);
            }

            $facebookUser = Socialite::driver('facebook')->stateless()->user();
            $result = $this->users->findOrCreateFromSocial('facebook', $facebookUser);
            
            return ApiResponse::success([
                'token' => $result['token'],
                'user' => $result['user'],
                'is_new_user' => $result['is_new_user']
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('Social authentication failed', 422, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Complete social login with additional company info
     */
    public function completeSocialOnboarding(CompleteSocialOnboardingRequest $request)
    {
        $user = $this->users->completeSocialOnboarding($request->user(), $request->validated());
        return ApiResponse::success($user);
    }
}