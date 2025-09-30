<?php

namespace App\Repositories\Contracts;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CompanyUserRepositoryInterface
{
    public function inviteUserToCompany(Company $company, array $data): User;
    public function listCompanyUsers(Company $company): Collection;
    public function findCompanyUser(Company $company, int $userId): ?User;
    public function updateCompanyUser(Company $company, int $userId, array $data): User;
    public function removeUserFromCompany(Company $company, int $userId): void;
}
