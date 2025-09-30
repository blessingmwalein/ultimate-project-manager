<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use App\Models\TaskList;

class TaskListTest extends TestCase
{
	use RefreshDatabase;

	public function test_can_create_task_list_for_project()
	{
		$user = User::factory()->create();
		$company = Company::factory()->create();
		$project = Project::factory()->create(['company_id' => $company->id]);
		$this->actingAs($user, 'sanctum');

		$response = $this->postJson("/api/v1/companies/{$company->id}/projects/{$project->id}/task-lists", [
			'name' => 'Backlog',
		]);

		$response->assertStatus(201);
		$this->assertDatabaseHas('task_lists', ['name' => 'Backlog', 'project_id' => $project->id]);
	}
}
