<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Company;
use App\Models\Project;
use App\Models\Inspection;
use App\Models\DailyLog;
use App\Models\ProjectMessage;
use App\Models\ProjectPhoto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Base user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')]
        );

        // Company and membership
        $company = Company::firstOrCreate(
            ['slug' => 'modern-family-home'],
            [
                'name' => 'Modern Family Home',
                'owner_user_id' => $user->id,
                'timezone' => 'Africa/Harare',
                'currency' => 'USD',
            ]
        );
        $company->users()->syncWithoutDetaching([$user->id => ['role' => 'admin']]);

        // Project (projects table uses `title` and status enums)
        $project = Project::firstOrCreate(
            ['company_id' => $company->id, 'title' => 'Modern Family Home'],
            [
                'description' => 'Residential construction project',
                'status' => 'in_progress',
            ]
        );

        // Inspections
        $inspections = [
            ['title' => 'Foundation Inspection', 'description' => 'Foundation and footing inspection before concrete pour', 'status' => 'scheduled', 'scheduled_date' => now()->addDays(3), 'council_officer' => 'John Doe', 'contact_email' => 'john.doe@council.gov'],
            ['title' => 'Structural Frame Inspection', 'description' => 'Structural frame and reinforcement inspection', 'status' => 'pending', 'scheduled_date' => now()->addDays(8), 'council_officer' => 'Jane Smith', 'contact_email' => 'jane.smith@council.gov'],
            ['title' => 'Rough Plumbing Inspection', 'description' => 'Rough plumbing installation inspection', 'status' => 'completed', 'scheduled_date' => now()->subDay(), 'council_officer' => 'Mike Johnson', 'contact_email' => 'mike.johnson@council.gov', 'reminder_sent' => true, 'last_reminder_at' => now()->subDay()],
            ['title' => 'Electrical Rough-in Inspection', 'description' => 'Electrical rough-in inspection', 'status' => 'overdue', 'scheduled_date' => now()->subDays(5), 'council_officer' => 'Sarah Davis', 'contact_email' => 'sarah.davis@council.gov', 'reminder_sent' => true, 'last_reminder_at' => now()->subDays(2)],
        ];
        foreach ($inspections as $i) {
            Inspection::updateOrCreate([
                'company_id' => $company->id,
                'project_id' => $project->id,
                'title' => $i['title'],
            ], array_merge($i, ['company_id' => $company->id, 'project_id' => $project->id]));
        }

        // Daily Logs
        $logs = [
            ['date' => now()->subDays(2)->toDateString(), 'weather' => 'Sunny', 'summary' => 'Site clearing and layout', 'manpower_count' => 12],
            ['date' => now()->subDay()->toDateString(), 'weather' => 'Cloudy', 'summary' => 'Foundation trenching', 'manpower_count' => 15],
            ['date' => now()->toDateString(), 'weather' => 'Light rain', 'summary' => 'Rebar setup, delayed pour', 'manpower_count' => 10],
        ];
        foreach ($logs as $log) {
            DailyLog::updateOrCreate([
                'company_id' => $company->id,
                'project_id' => $project->id,
                'date' => $log['date'],
            ], array_merge($log, ['company_id' => $company->id, 'project_id' => $project->id]));
        }

        // Chat messages
        foreach (['Welcome to the project!', 'Remember safety briefing at 8 AM.', 'New client document uploaded.'] as $text) {
            ProjectMessage::create([
                'company_id' => $company->id,
                'project_id' => $project->id,
                'user_id' => $user->id,
                'message' => $text,
            ]);
        }

        // Photos
        $photoUrls = [
            'https://picsum.photos/seed/site1/800/600',
            'https://picsum.photos/seed/site2/800/600',
            'https://picsum.photos/seed/site3/800/600',
            'https://picsum.photos/seed/site4/800/600',
        ];
        foreach ($photoUrls as $idx => $url) {
            ProjectPhoto::create([
                'company_id' => $company->id,
                'project_id' => $project->id,
                'url' => $url,
                'caption' => 'Site Photo ' . ($idx + 1),
                'taken_at' => now()->subDays(4 - $idx),
            ]);
        }

        // Sample notifications
        $user->notify(new \App\Notifications\GenericDatabaseNotification('Budget Alert', 'Action Required', 'Modern Family Home project is 85% of budget used with 63% completion'));
        $user->notify(new \App\Notifications\GenericDatabaseNotification('Milestone Completed', 'Success', 'Foundation work completed ahead of schedule'));
        $user->notify(new \App\Notifications\GenericDatabaseNotification('Weather Alert', 'Info', 'Heavy rain forecast for next 3 days - consider indoor work'));
        $user->notify(new \App\Notifications\GenericDatabaseNotification('Inspection Due', 'Action Required', 'Electrical inspection required by tomorrow'));
        $user->notify(new \App\Notifications\GenericDatabaseNotification('Client Message', 'Info', 'Client uploaded new requirements document'));
    }
}
