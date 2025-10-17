<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository
{
    protected $model;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    /**
     * Get all companies with pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['owner', 'users'])->paginate($perPage);
    }

    /**
     * Find company by ID
     */
    public function findById(int $id): ?Company
    {
        return $this->model->with(['owner', 'users', 'projects'])->find($id);
    }

    /**
     * Create a new company
     */
    public function create(array $data): Company
    {
        return $this->model->create($data);
    }

    /**
     * Update company by ID
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete company by ID
     */
    public function delete(int $id): bool
    {
        $company = $this->findById($id);
        if ($company) {
            // Detach all users first
            $company->users()->detach();
            return $company->delete();
        }
        return false;
    }

    /**
     * Find companies by owner ID
     */
    public function findByOwnerId(int $ownerId): Collection
    {
        return $this->model->where('owner_id', $ownerId)->get();
    }

    /**
     * Find companies by user ID (where user is a member)
     */
    public function findByUserId(int $userId): Collection
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    }

    /**
     * Search companies by name
     */
    public function searchByName(string $name): Collection
    {
        return $this->model->where('name', 'LIKE', '%' . $name . '%')->get();
    }

    /**
     * Get companies with active status
     */
    public function getActive(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }
}