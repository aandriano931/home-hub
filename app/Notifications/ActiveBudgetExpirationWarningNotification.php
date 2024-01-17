<?php

namespace App\Notifications;

use App\Models\Budget\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class ActiveBudgetExpirationWarningNotification extends Notification
{
    use Queueable;

    private Budget $budget;

    private string $formatedExpirationDate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
        $this->formatedExpirationDate = Carbon::parse($budget->expiration_date)->isoFormat('DD MMMM YYYY');;
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
            ->subject("(FamilyHub) Le budget : {$this->budget->label} va expirer")
            ->greeting('Coucou!')
            ->line("Le budget mensuel actif arrivera à expiration le {$this->formatedExpirationDate}!")
            ->action('Créer ou modifier un budget:', url('/admin/budgets'))
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

}
