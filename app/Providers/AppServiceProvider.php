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
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

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
	}

	public function boot(): void
	{
		Scramble::configure()
			->withDocumentTransformers(function (OpenApi $openApi) {
				$openApi->secure(SecurityScheme::http('bearer'));
			});
	}
}
