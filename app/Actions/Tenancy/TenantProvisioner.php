<?php

namespace App\Actions\Tenancy;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Exceptions\DomainOccupiedByOtherTenantException;

class TenantProvisioner
{
	public function provision(string $companyName, string $domain, array $meta = []): Tenant
	{
		// Pre-validate: domain must be unique across tenants
		if (Domain::query()->where('domain', $domain)->exists()) {
			throw ValidationException::withMessages([
				'domain' => ["The domain '{$domain}' is already taken."],
			]);
		}

		// Central writes inside a transaction
		$tenant = DB::transaction(function () use ($companyName, $domain, $meta) {
			$tenant = Tenant::create([
				'data' => array_merge(['company_name' => $companyName], $meta),
			]);

			try {
				Domain::create([
					'tenant_id' => $tenant->id,
					'domain' => $domain,
				]);
			} catch (DomainOccupiedByOtherTenantException $e) {
				throw ValidationException::withMessages([
					'domain' => ["The domain '{$domain}' is already taken."],
				]);
			}

			return $tenant;
		});

		// Run tenant migrations OUTSIDE the central transaction
		tenancy()->initialize($tenant);
		try {
			Artisan::call('migrate', [
				'--path' => database_path('migrations/tenant'),
				'--realpath' => true,
				'--force' => true,
			]);
		} finally {
			tenancy()->end();
		}

		return $tenant;
	}
}
