<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Models\User;
use App\Repositories\Contracts\CompanyUserRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class CompanyUserRepository implements CompanyUserRepositoryInterface
{
    public function inviteUserToCompany(Company $company, array $data): User
    {
        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            $password = Str::random(12);
            $user = User::create([
                'name' => $data['name'] ?? $data['email'],
                'email' => $data['email'],
                'password' => Hash::make($password),
            ]);
            // dispatch invitation email job with $password or magic link
            $user->notify(new \App\Notifications\InviteUserNotification($company, $password));
        }

        $company->users()->syncWithoutDetaching([$user->id => ['role' => $data['role']]]);

        return $user;
    }

    public function listCompanyUsers(Company $company): Collection
    {
        return $company->users()->withPivot('role')->get();
    }

    public function findCompanyUser(Company $company, int $userId): ?User
    {
        return $company->users()->where('user_id', $userId)->withPivot('role')->first();
    }

    public function updateCompanyUser(Company $company, int $userId, array $data): User
    {
        $company->users()->updateExistingPivot($userId, ['role' => $data['role']]);
        return $this->findCompanyUser($company, $userId);
    }

    public function removeUserFromCompany(Company $company, int $userId): void
    {
        $company->users()->detach($userId);
    }
}
