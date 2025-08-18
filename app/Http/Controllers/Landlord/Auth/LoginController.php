<?php

namespace App\Http\Controllers\Landlord\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
	public function store(Request $request)
	{
		$validated = $request->validate([
			'email' => ['required','email'],
			'password' => ['required','string'],
			'device_name' => ['sometimes','string','max:100'],
		]);

		$user = User::query()->where('email', $validated['email'])->first();

		if (! $user || ! Hash::check($validated['password'], $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}

		$token = $user->createToken($validated['device_name'] ?? 'admin')->plainTextToken;

		return response()->json([
			'data' => [
				'token' => $token,
				'user' => [
					'id' => $user->id,
					'name' => $user->name,
					'email' => $user->email,
				],
			],
		]);
	}

	public function destroy(Request $request)
	{
		if ($request->user() && $request->user()->currentAccessToken()) {
			$request->user()->currentAccessToken()->delete();
		}

		return response()->noContent();
	}
}
