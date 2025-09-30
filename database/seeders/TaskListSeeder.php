<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\TaskList;

class TaskListSeeder extends Seeder
{
	public function run()
	{
		$defaults = ['To Do','In Progress','Done'];
		Project::withTrashed()->get()->each(function ($project) use ($defaults) {
			foreach ($defaults as $i => $name) {
				TaskList::firstOrCreate([
					'company_id' => $project->company_id,
					'project_id' => $project->id,
					'name' => $name,
				], [
					'order_index' => $i,
				]);
			}
		});
	}
}
