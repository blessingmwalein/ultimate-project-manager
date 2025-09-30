<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Eloquent\ProjectRepository;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Eloquent\CompanyRepository;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Eloquent\PlanRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Contracts\CompanyPlanRepositoryInterface;
use App\Repositories\Eloquent\CompanyPlanRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Contracts\TaskListRepositoryInterface;
use App\Repositories\Eloquent\TaskListRepository;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Repositories\Eloquent\BudgetRepository;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Repositories\Eloquent\ExpenseRepository;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use App\Repositories\Contracts\CompanyUserRepositoryInterface;
use App\Repositories\Eloquent\CompanyUserRepository;
use App\Repositories\Contracts\InspectionRepositoryInterface;
use App\Repositories\Eloquent\InspectionRepository;
use App\Repositories\Contracts\DailyLogRepositoryInterface;
use App\Repositories\Eloquent\DailyLogRepository;
use App\Repositories\Contracts\UserPlanRepositoryInterface;
use App\Repositories\Eloquent\UserPlanRepository;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
		$this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
		$this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
		$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
		$this->app->bind(CompanyPlanRepositoryInterface::class, CompanyPlanRepository::class);
		$this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
		$this->app->bind(TaskListRepositoryInterface::class, TaskListRepository::class);
		$this->app->bind(BudgetRepositoryInterface::class, BudgetRepository::class);
		$this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
		$this->app->bind(CompanyUserRepositoryInterface::class, CompanyUserRepository::class);
        $this->app->bind(InspectionRepositoryInterface::class, InspectionRepository::class);
        $this->app->bind(DailyLogRepositoryInterface::class, DailyLogRepository::class);
        $this->app->bind(UserPlanRepositoryInterface::class, UserPlanRepository::class);
	}

	public function boot(): void
	{
		Scramble::configure()
			->withDocumentTransformers(function (OpenApi $openApi) {
				$openApi->secure(SecurityScheme::http('bearer'));
			});
	}
}
