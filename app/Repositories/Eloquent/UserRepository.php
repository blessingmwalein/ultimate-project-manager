<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
	public function register(array $attributes): array
	{
		$user = User::create([
			'name' => $attributes['name'],
			'email' => $attributes['email'],
			'password' => Hash::make($attributes['password']),
		]);
		$token = $user->createToken('onboarding')->plainTextToken;
		return ['user' => $user, 'token' => $token];
	}

	public function login(string $email, string $password, ?string $deviceName = null): array
	{
		$user = User::where('email', $email)->first();
		abort_unless($user && Hash::check($password, $user->password), 401);
		$token = $user->createToken($deviceName ?? 'web')->plainTextToken;
		return ['user' => $user, 'token' => $token];
	}

	public function updateProfile(User $user, array $attributes): User
	{
		$user->fill($attributes);
		$user->save();
		return $user;
	}

	public function findOrCreateFromSocial(string $provider, $socialUser): array
	{
		// Check if user exists by email
		$user = User::where('email', $socialUser->getEmail())->first();
		$isNewUser = false;

		if (!$user) {
			// Create new user from social provider
			$user = User::create([
				'name' => $socialUser->getName(),
				'email' => $socialUser->getEmail(),
				'email_verified_at' => now(),
				'avatar' => $socialUser->getAvatar(),
				'provider' => $provider,
				'provider_id' => $socialUser->getId(),
				'password' => Hash::make(Str::random(32)), // Random password for social users
			]);
			$isNewUser = true;
		} else {
			// Update existing user with social provider info if not already linked
			if (!$user->provider || !$user->provider_id) {
				$user->update([
					'provider' => $provider,
					'provider_id' => $socialUser->getId(),
					'avatar' => $socialUser->getAvatar(),
				]);
			}
		}

		// Create token
		$token = $user->createToken('social-auth')->plainTextToken;

		return [
			'user' => $user->fresh(),
			'token' => $token,
			'is_new_user' => $isNewUser
		];
	}

	public function completeSocialOnboarding($user, array $data)
	{
		$user->update(array_filter([
			'phone' => $data['phone'] ?? null,
			'company_name' => $data['company_name'] ?? null,
			'job_title' => $data['job_title'] ?? null,
		]));

		return $user->fresh();
	}
}
