<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landlord\Auth\RegisterCompanyController;
use App\Http\Controllers\Landlord\Tenants\TenantAdminController;
use App\Http\Controllers\Landlord\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Landlord\Companies\CompanyController;
use App\Http\Controllers\Landlord\Plans\PlanController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Projects\ProjectController as CompanyProjectController;
use App\Http\Controllers\Tasks\TaskController as CompanyTaskController;

Route::prefix('api/v1')->group(function () {
	// Onboarding (single-db now)
	Route::post('/auth/register-user', [OnboardingController::class, 'register']);
	Route::post('/auth/login-user', [OnboardingController::class, 'login']);
	Route::middleware('auth:sanctum')->group(function () {
		Route::post('/auth/complete-profile', [OnboardingController::class, 'completeProfile']);
		Route::post('/companies', [OnboardingController::class, 'createCompany']);
		Route::post('/companies/{companyId}/select-plan', [OnboardingController::class, 'selectPlan']);

		// Company projects
		Route::get('/companies/{companyId}/projects', [CompanyProjectController::class, 'index']);
		Route::post('/companies/{companyId}/projects', [CompanyProjectController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{id}', [CompanyProjectController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{id}', [CompanyProjectController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{id}', [CompanyProjectController::class, 'destroy']);

		// Project tasks
		Route::get('/companies/{companyId}/projects/{projectId}/tasks', [CompanyTaskController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/tasks', [CompanyTaskController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{projectId}/tasks/{id}', [CompanyTaskController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{projectId}/tasks/{id}', [CompanyTaskController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{projectId}/tasks/{id}', [CompanyTaskController::class, 'destroy']);
	});

	// Admin auth
	Route::post('/auth/login', [AdminLoginController::class, 'store']);
	Route::post('/auth/logout', [AdminLoginController::class, 'destroy'])->middleware('auth:sanctum');

	// Admin management
	Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
		Route::get('/plans', [PlanController::class, 'index']);
		Route::post('/plans', [PlanController::class, 'store']);
		Route::put('/plans/{id}', [PlanController::class, 'update']);
		Route::delete('/plans/{id}', [PlanController::class, 'destroy']);

		Route::get('/companies', [CompanyController::class, 'index']);
		Route::get('/companies/{id}', [CompanyController::class, 'show']);
		Route::post('/companies', [CompanyController::class, 'store']);
		Route::put('/companies/{id}', [CompanyController::class, 'update']);
		Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);
	});
});
