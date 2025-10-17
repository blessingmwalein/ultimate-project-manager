<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
	public function register(array $attributes): array; // returns ['user'=>User, 'token'=>string]

	public function login(string $email, string $password, ?string $deviceName = null): array; // returns ['user'=>User, 'token'=>string]

	public function updateProfile(User $user, array $attributes): User;

	/**
	 * Find or create user from social provider
	 */
	public function findOrCreateFromSocial(string $provider, $socialUser): array;

	/**
	 * Complete social onboarding with additional info
	 */
	public function completeSocialOnboarding($user, array $data);
}
