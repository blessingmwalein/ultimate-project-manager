<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use App\Repositories\UserRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class SocialLoginService
{
    protected $userRepository;
    protected $companyRepository;

    public function __construct(UserRepository $userRepository, CompanyRepository $companyRepository)
    {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Get Google OAuth redirect URL
     */
    public function getGoogleRedirectUrl(): string
    {
        $params = [
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'scope' => 'openid profile email',
            'response_type' => 'code',
            'access_type' => 'offline',
            'state' => Str::random(40)
        ];

        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }

    /**
     * Get Facebook OAuth redirect URL
     */
    public function getFacebookRedirectUrl(): string
    {
        $params = [
            'client_id' => config('services.facebook.client_id'),
            'redirect_uri' => config('services.facebook.redirect'),
            'scope' => 'email,public_profile',
            'response_type' => 'code',
            'state' => Str::random(40)
        ];

        return 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query($params);
    }

    /**
     * Handle Google callback and create/login user
     */
    public function handleGoogleCallback(string $code, ?string $state = null): array
    {
        // Exchange code for access token
        $tokenResponse = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('services.google.redirect'),
        ]);

        if (!$tokenResponse->successful()) {
            throw new \Exception('Failed to exchange code for access token');
        }

        $tokenData = $tokenResponse->json();

        // Get user info from Google
        $userResponse = Http::withToken($tokenData['access_token'])
            ->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if (!$userResponse->successful()) {
            throw new \Exception('Failed to fetch user information from Google');
        }

        $googleUser = $userResponse->json();

        return $this->handleSocialUser($googleUser, 'google');
    }

    /**
     * Handle Facebook callback and create/login user
     */
    public function handleFacebookCallback(string $code, ?string $state = null): array
    {
        // Exchange code for access token
        $tokenResponse = Http::get('https://graph.facebook.com/v18.0/oauth/access_token', [
            'client_id' => config('services.facebook.client_id'),
            'client_secret' => config('services.facebook.client_secret'),
            'code' => $code,
            'redirect_uri' => config('services.facebook.redirect'),
        ]);

        if (!$tokenResponse->successful()) {
            throw new \Exception('Failed to exchange code for access token');
        }

        $tokenData = $tokenResponse->json();

        // Get user info from Facebook
        $userResponse = Http::get('https://graph.facebook.com/v18.0/me', [
            'access_token' => $tokenData['access_token'],
            'fields' => 'id,name,email,first_name,last_name,picture'
        ]);

        if (!$userResponse->successful()) {
            throw new \Exception('Failed to fetch user information from Facebook');
        }

        $facebookUser = $userResponse->json();

        return $this->handleSocialUser($facebookUser, 'facebook');
    }

    /**
     * Handle social user data and create/login user
     */
    protected function handleSocialUser(array $socialUser, string $provider): array
    {
        $email = $socialUser['email'] ?? null;
        
        if (!$email) {
            throw new \Exception('Email not provided by ' . ucfirst($provider));
        }

        // Check if user already exists
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            // Update social provider info if not set
            if (!$user->social_provider) {
                $this->userRepository->update($user->id, [
                    'social_provider' => $provider,
                    'social_id' => $socialUser['id']
                ]);
            }
        } else {
            // Create new user with social data
            $userData = $this->prepareSocialUserData($socialUser, $provider);
            $user = $this->userRepository->create($userData);
        }

        // Generate access token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'needs_company_setup' => !$user->companies()->exists(),
            'social_data' => $this->extractCompanyDataFromSocial($socialUser, $provider)
        ];
    }

    /**
     * Prepare user data from social provider
     */
    protected function prepareSocialUserData(array $socialUser, string $provider): array
    {
        $name = $socialUser['name'] ?? '';
        
        // Extract first and last name
        if ($provider === 'google') {
            $firstName = $socialUser['given_name'] ?? '';
            $lastName = $socialUser['family_name'] ?? '';
        } else { // Facebook
            $firstName = $socialUser['first_name'] ?? '';
            $lastName = $socialUser['last_name'] ?? '';
        }

        return [
            'name' => $name,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $socialUser['email'],
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(16)), // Random password since they login via social
            'social_provider' => $provider,
            'social_id' => $socialUser['id'],
            'avatar' => $socialUser['picture'] ?? ($socialUser['picture']['data']['url'] ?? null),
            'profile_completed' => true, // Auto-complete since we have basic info
        ];
    }

    /**
     * Extract potential company data from social profile
     */
    protected function extractCompanyDataFromSocial(array $socialUser, string $provider): array
    {
        $suggestions = [];

        // Extract name parts for potential company name suggestions
        if ($provider === 'google') {
            $firstName = $socialUser['given_name'] ?? '';
            $lastName = $socialUser['family_name'] ?? '';
        } else {
            $firstName = $socialUser['first_name'] ?? '';
            $lastName = $socialUser['last_name'] ?? '';
        }

        if ($firstName && $lastName) {
            $suggestions['company_name_suggestions'] = [
                $firstName . ' ' . $lastName . ' Construction',
                $lastName . ' & Associates',
                $firstName . ' ' . $lastName . ' Contractors',
            ];
        }

        return $suggestions;
    }

    /**
     * Complete social onboarding with company setup
     */
    public function completeSocialOnboarding(User $user, array $companyData): array
    {
        // Create company for the user
        $company = $this->companyRepository->create([
            'name' => $companyData['company_name'],
            'type' => $companyData['company_type'],
            'phone' => $companyData['phone'] ?? null,
            'address' => $companyData['address'] ?? null,
            'owner_id' => $user->id,
            'status' => 'active'
        ]);

        // Attach user to company as admin
        $company->users()->attach($user->id, [
            'role' => 'admin',
            'status' => 'active',
            'invited_at' => now(),
            'joined_at' => now()
        ]);

        return [
            'company' => $company,
            'user' => $user->fresh(),
            'onboarding_completed' => true
        ];
    }
}