<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Project;

class BudgetOverrunAlert extends Notification
{
    use Queueable;

    public Project $project;
    public int $budgetItemId;

    public function __construct(Project $project, int $budgetItemId)
    {
        $this->project = $project;
        $this->budgetItemId = $budgetItemId;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Budget overrun detected')
            ->line("A budget item in project {$this->project->title} has exceeded its planned cost by more than 10%.")
            ->action('View Project', url("/projects/{$this->project->id}"));
    }

    public function toDatabase($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            'budget_item_id' => $this->budgetItemId,
            'message' => 'Budget item exceeded planned cost by >10%',
        ];
    }
}
