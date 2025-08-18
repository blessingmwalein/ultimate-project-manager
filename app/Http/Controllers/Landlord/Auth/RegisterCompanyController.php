<?php

namespace App\Http\Controllers\Landlord\Auth;

use App\Actions\Tenancy\TenantProvisioner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterCompanyController extends Controller
{
	public function store(Request $request, TenantProvisioner $provisioner)
	{
		$validated = $request->validate([
			'company_name' => ['required','string','max:255'],
			'subdomain' => ['required','string','alpha_dash','max:63'],
			'owner_name' => ['required','string','max:255'],
			'owner_email' => ['required','email','max:255'],
			'password' => ['required','string','min:8'],
		]);

		$domain = Str::lower($validated['subdomain']).'.'.config('app.url_base', 'localhost');

		$tenant = $provisioner->provision($validated['company_name'], $domain, []);

		return response()->json([
			'data' => [
				'tenant_id' => $tenant->id,
				'domain' => $domain,
			],
		], 201);
	}
}
