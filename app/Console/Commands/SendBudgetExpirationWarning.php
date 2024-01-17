<?php
 
namespace App\Console\Commands;
 
use App\Mail\ActiveBudgetExpirationWarning;
use App\Models\Budget\Budget;
use App\Notifications\ActiveBudgetExpirationWarningNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendBudgetExpirationWarning extends Command
{
    private const WARNING_DELAY = 7;

    /**
     * @var string
     */
    protected $signature = 'budget:expiration:warn';
 
    /**
     * @var string
     */
    protected $description = 'Send a warning email to the active budget participants if its expiration date is close.';
 
    public function handle(): void
    {
        //Get the active budget
        $activeBudget = Budget::get()->where('is_active', '=', true)->first();

        //Compare the active budget expiration_date to the actual date
        $expiration_date = Carbon::parse($activeBudget->expiration_date);
        $gapInDays = Carbon::now()->diffInDays($expiration_date);

        //If the expiration date - actual date = warning delay send an email to participants
        // if($gapInDays === self::WARNING_DELAY) {
            foreach ($activeBudget->contributors as $contributor) {
                if($contributor->label === 'Arnaud') {
                    $notification = new ActiveBudgetExpirationWarningNotification($activeBudget);
                    $contributor->user->notify($notification);
                }
            }
        // }

    }
}
