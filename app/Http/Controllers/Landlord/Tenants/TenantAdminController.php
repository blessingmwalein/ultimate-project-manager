<?php

namespace App\Http\Controllers\Landlord\Tenants;

use App\Actions\Tenancy\TenantProvisioner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantAdminController extends Controller
{
	public function store(Request $request, TenantProvisioner $provisioner)
	{
		$validated = $request->validate([
			'company_name' => ['required','string','max:255'],
			'domain' => ['required','string'],
		]);

		$tenant = $provisioner->provision($validated['company_name'], $validated['domain']);

		return response()->json(['data' => [
			'tenant_id' => $tenant->id,
			'domain' => $validated['domain'],
		]], 201);
	}
}
