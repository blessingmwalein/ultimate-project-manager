<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find user by social provider and ID
     */
    public function findBySocialId(string $provider, string $socialId): ?User
    {
        return $this->model->where('social_provider', $provider)
                          ->where('social_id', $socialId)
                          ->first();
    }

    /**
     * Create a new user
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update user by ID
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Get all users
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Delete user by ID
     */
    public function delete(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Find users by company ID
     */
    public function findByCompanyId(int $companyId): Collection
    {
        return $this->model->whereHas('companies', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->get();
    }

    /**
     * Find or create a user from social provider
     */
    public function findOrCreateFromSocial(string $provider, $socialUser): array
    {
        // Check if user exists by email
        $user = $this->model->where('email', $socialUser->getEmail())->first();
        $isNewUser = false;

        if (!$user) {
            // Create new user from social provider
            $user = $this->model->create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'avatar' => $socialUser->getAvatar(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => bcrypt(Str::random(32)), // Random password for social users
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
        $token = $user->createToken('auth-token', ['*'])->plainTextToken;

        return [
            'user' => $user->fresh(),
            'token' => $token,
            'is_new_user' => $isNewUser
        ];
    }

    /**
     * Complete social onboarding for a user
     */
    public function completeSocialOnboarding($user, array $data)
    {
        return $user->update(array_filter([
            'phone' => $data['phone'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'job_title' => $data['job_title'] ?? null,
        ]));
    }
}