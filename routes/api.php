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
use App\Http\Controllers\Projects\TaskListController as CompanyTaskListController;
use App\Http\Controllers\Projects\BudgetController as CompanyBudgetController;
use App\Http\Controllers\Projects\ExpenseController as CompanyExpenseController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Auth\InviteAcceptanceController;
use App\Http\Controllers\Companies\CompanyUserController;
use App\Http\Controllers\Companies\CompanyProfileController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Projects\InspectionController as CompanyInspectionController;
use App\Http\Controllers\Projects\DailyLogController as CompanyDailyLogController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Projects\ProjectChatController;
use App\Http\Controllers\Projects\ProjectMediaController;
use App\Http\Controllers\InsightsController;

Route::prefix('api/v1')->group(function () {
	// Public plans listing (no auth)
	Route::get('/plans', [PlanController::class, 'index']);
	// Onboarding (single-db now)
	Route::post('/auth/register-user', [OnboardingController::class, 'register']);
	Route::post('/auth/login-user', [OnboardingController::class, 'login']);
	Route::middleware('auth:sanctum')->group(function () {
		Route::post('/auth/complete-profile', [OnboardingController::class, 'completeProfile']);
		Route::post('/companies', [OnboardingController::class, 'createCompany']);
		Route::post('/companies/{companyId}/select-plan', [OnboardingController::class, 'selectPlan']);
		Route::post('/user/select-plan', [OnboardingController::class, 'selectUserPlan']);

		// Notifications
		Route::get('/notifications', [NotificationsController::class, 'index']);
		Route::post('/notifications/mark-all-read', [NotificationsController::class, 'markAllRead']);

		// User profile management
		Route::get('/profile', [UserProfileController::class, 'show']);
		Route::put('/profile', [UserProfileController::class, 'update']);
		Route::post('/profile/change-password', [UserProfileController::class, 'changePassword']);

		// Company profile management
		Route::get('/companies/{companyId}/profile', [CompanyProfileController::class, 'show']);
		Route::put('/companies/{companyId}/profile', [CompanyProfileController::class, 'update']);

		// Company user management
		Route::get('/companies/{companyId}/users', [CompanyUserController::class, 'index']);
		Route::post('/companies/{companyId}/users', [CompanyUserController::class, 'store']);
		Route::get('/companies/{companyId}/users/{userId}', [CompanyUserController::class, 'show']);
		Route::put('/companies/{companyId}/users/{userId}', [CompanyUserController::class, 'update']);
		Route::delete('/companies/{companyId}/users/{userId}', [CompanyUserController::class, 'destroy']);

		// Dashboard statistics
		Route::get('/companies/{companyId}/stats', [DashboardController::class, 'companyStats']);
		Route::get('/companies/{companyId}/projects/{projectId}/stats', [DashboardController::class, 'projectStats']);

		// Company projects
		Route::get('/companies/{companyId}/projects', [CompanyProjectController::class, 'index']);
		Route::post('/companies/{companyId}/projects', [CompanyProjectController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{id}', [CompanyProjectController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{id}', [CompanyProjectController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{id}', [CompanyProjectController::class, 'destroy']);

		// Project inspections
		Route::get('/companies/{companyId}/projects/{projectId}/inspections', [CompanyInspectionController::class, 'index']);
		Route::get('/companies/{companyId}/projects/{projectId}/inspections/summary', [CompanyInspectionController::class, 'summary']);
		Route::post('/companies/{companyId}/projects/{projectId}/inspections', [CompanyInspectionController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{projectId}/inspections/{id}', [CompanyInspectionController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{projectId}/inspections/{id}', [CompanyInspectionController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{projectId}/inspections/{id}', [CompanyInspectionController::class, 'destroy']);
		Route::post('/companies/{companyId}/projects/{projectId}/inspections/{id}/send-reminder', [CompanyInspectionController::class, 'sendReminder']);
		Route::post('/companies/{companyId}/projects/{projectId}/inspections/send-email', [CompanyInspectionController::class, 'sendEmail']);

		// Daily logs
		Route::get('/companies/{companyId}/projects/{projectId}/daily-logs', [CompanyDailyLogController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/daily-logs', [CompanyDailyLogController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{projectId}/daily-logs/{id}', [CompanyDailyLogController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{projectId}/daily-logs/{id}', [CompanyDailyLogController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{projectId}/daily-logs/{id}', [CompanyDailyLogController::class, 'destroy']);

		// Chat
		Route::get('/companies/{companyId}/projects/{projectId}/chat/messages', [ProjectChatController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/chat/messages', [ProjectChatController::class, 'store']);

		// Media (site photos)
		Route::get('/companies/{companyId}/projects/{projectId}/photos', [ProjectMediaController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/photos', [ProjectMediaController::class, 'store']);
		Route::delete('/companies/{companyId}/projects/{projectId}/photos/{id}', [ProjectMediaController::class, 'destroy']);

		// AI Insights
		Route::get('/companies/{companyId}/projects/{projectId}/insights', [InsightsController::class, 'project']);

		// Project task-lists
		Route::get('/companies/{companyId}/projects/{projectId}/task-lists', [CompanyTaskListController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/task-lists', [CompanyTaskListController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{projectId}/task-lists/{id}', [CompanyTaskListController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{projectId}/task-lists/{id}', [CompanyTaskListController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{projectId}/task-lists/{id}', [CompanyTaskListController::class, 'destroy']);

		// Project tasks
		Route::get('/companies/{companyId}/projects/{projectId}/tasks', [CompanyTaskController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/tasks', [CompanyTaskController::class, 'store']);
		Route::get('/companies/{companyId}/projects/{projectId}/tasks/{id}', [CompanyTaskController::class, 'show']);
		Route::put('/companies/{companyId}/projects/{projectId}/tasks/{id}', [CompanyTaskController::class, 'update']);
		Route::delete('/companies/{companyId}/projects/{projectId}/tasks/{id}', [CompanyTaskController::class, 'destroy']);
		Route::post('/companies/{companyId}/projects/{projectId}/tasks/{id}/move', [CompanyTaskController::class, 'move']);

		// Budget & BOQ
		Route::get('/companies/{companyId}/projects/{projectId}/budget/categories', [CompanyBudgetController::class, 'categories']);
		Route::post('/companies/{companyId}/projects/{projectId}/budget/categories', [CompanyBudgetController::class, 'storeCategory']);
		Route::put('/companies/{companyId}/projects/{projectId}/budget/categories/{id}', [CompanyBudgetController::class, 'updateCategory']);
		Route::delete('/companies/{companyId}/projects/{projectId}/budget/categories/{id}', [CompanyBudgetController::class, 'deleteCategory']);

		Route::get('/companies/{companyId}/projects/{projectId}/budget/items', [CompanyBudgetController::class, 'items']);
		Route::post('/companies/{companyId}/projects/{projectId}/budget/items', [CompanyBudgetController::class, 'storeItem']);
		Route::put('/companies/{companyId}/projects/{projectId}/budget/items/{id}', [CompanyBudgetController::class, 'updateItem']);
		Route::delete('/companies/{companyId}/projects/{projectId}/budget/items/{id}', [CompanyBudgetController::class, 'deleteItem']);

		// Expenses
		Route::get('/companies/{companyId}/projects/{projectId}/expenses', [CompanyExpenseController::class, 'index']);
		Route::post('/companies/{companyId}/projects/{projectId}/expenses', [CompanyExpenseController::class, 'store']);
		Route::delete('/companies/{companyId}/projects/{projectId}/expenses/{id}', [CompanyExpenseController::class, 'destroy']);
		Route::get('/companies/{companyId}/projects/{projectId}/expenses/{id}/receipt', [CompanyExpenseController::class, 'receipt']);

		// Uploads
		Route::post('/companies/{companyId}/projects/{projectId}/upload/receipt', [UploadController::class, 'storeReceipt']);
		Route::get('/companies/{companyId}/projects/{projectId}/upload/signed-url', [UploadController::class, 'signedUploadUrl']);
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

	// Magic login token exchange
	Route::post('/auth/magic-login', [\App\Http\Controllers\Auth\MagicLoginController::class, 'exchange']);

	// Invite acceptance endpoints (frontend will call these after redirect)
	Route::get('/invites/accept', [InviteAcceptanceController::class, 'show']);
	Route::post('/invites/accept', [InviteAcceptanceController::class, 'setPassword']);
});
