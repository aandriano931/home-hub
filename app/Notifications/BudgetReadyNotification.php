<?php

namespace App\Notifications;

use App\Models\Budget\Budget;
use App\Models\Budget\BudgetContributor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetReadyNotification extends Notification
{
    use Queueable;

    private Budget $budget;

    /**
     * Create a new notification instance.
     */
    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = new MailMessage();
        $message
            ->subject("(FamilyHub) Virements budget mensuel : {$this->budget->label}")
            ->greeting('Coucou!')
            ->line("Les virements à mettre place pour le budget mensuel en cours sont les suivants : ");
        foreach($this->budget->contributors as $contributor) {
            $message->line($contributor->label . " : {$this->calculatePart($contributor)} €");
        }
        $message
            ->action('Consulter les détails du budget', url('/budget-configurator'))
            ->line('Bonne journée,')
            ->salutation('Nonobot.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    private function getBudgetTotalDebit(Budget $budget): float
    {
        $totalDebit = 0;
        foreach ($budget->budgetLines as $budgetLine) {
            $totalDebit += $budgetLine->debit;
        }

        return $totalDebit;
    }

    private function getBudgetTotalCredit(Budget $budget): float
    {
        $totalCredit = 0;
        foreach ($budget->budgetLines as $budgetLine) {
            $totalCredit += $budgetLine->credit;
        }

        return $totalCredit;
    }

    private function calculateRatio(Budget $budget, BudgetContributor $budgetContributor): float
    {
        $totalAvailable = 0;
        foreach($budget->contributors as $contributor) {
            $totalAvailable += $contributor->available_money;
        }

        return round($budgetContributor->available_money / $totalAvailable, 3);
    }

    private function calculatePart(BudgetContributor $budgetContributor): float
    {
        $ratio = $this->calculateRatio($this->budget, $budgetContributor);
        $totalCredit = $this->getBudgetTotalCredit($this->budget);
        $totalDebit = $this->getBudgetTotalDebit($this->budget);

        return round($ratio * ($totalDebit - $totalCredit), -1);
    }
}
