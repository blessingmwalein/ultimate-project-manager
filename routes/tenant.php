<?php
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
	InitializeTenancyByDomain::class,
	PreventAccessFromCentralDomains::class,
	'auth:sanctum',
])
	->prefix('api/v1')
	->group(function () {
		Route::apiResource('projects', App\Http\Controllers\Tenant\ProjectController::class);
		// ...add other tenant routes
	});
