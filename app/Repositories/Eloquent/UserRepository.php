<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

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
}
