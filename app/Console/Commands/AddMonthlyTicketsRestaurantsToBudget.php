<?php
 
namespace App\Console\Commands;
 
use App\Repository\Bank\TransactionRepository;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddMonthlyTicketsRestaurantsToBudget extends Command
{
    private const DEFAULT_TR_DEBIT = 175.00;
    private const DEFAULT_TR_LABEL = 'SIMULATION DEPENSES TICKETS RESTAURANTS';

    private const TR_EXCLUDED_MONTHS = [
        '04/2024',
        '05/2024',
    ];

    /**
     * @var string
     */
    protected $signature = 'budget:add-tickets-resto';
 
    /**
     * @var string
     */
    protected $description = 'Add a ticket restaurant bank transaction for every months if it doesn\'t already exist.';
 
    public function handle(): void
    {
        //Get a list of all months between the first transaction and the last one
        $transactions = DB::table('bank_transaction')->orderBy('operation_date', 'asc')->get();
        $start = Carbon::parse(TransactionRepository::DEFAULT_START_DATE);
        $end = Carbon::parse($transactions->last()->operation_date);
        $period = CarbonPeriod::create($start, '1 month', $end);
        $formattedMonths = [];
        foreach ($period as $date) {
            $formattedMonths[] = $date->format('m/Y');
        }

        //Select all existing bank transactions with the ticket restaurant label
        $transactionsWithTicketResto = DB::table('bank_transaction')->where('label', '=', self::DEFAULT_TR_LABEL)->orderBy('operation_date', 'asc')->get();
        $formattedMonthsWithTicketResto = [];
        foreach ($transactionsWithTicketResto as $transactionWithTicketResto) {
            $formattedMonthsWithTicketResto[] = Carbon::parse($transactionWithTicketResto->operation_date)->format('m/Y');
        }

        //If a ticket restaurant bank transaction already exist for a month in the list remove this month
        $monthsRequiringTicketResto = array_diff($formattedMonths, $formattedMonthsWithTicketResto);

        //Remove the excluded months from the list
        $monthsRequiringTicketResto = array_diff($monthsRequiringTicketResto, self::TR_EXCLUDED_MONTHS);

        //For all months without it, insert the ticket restaurant default bank transaction with value_date and operation_date as the last day of the month
        $jointAccount = DB::table('bank_account')->where('alias', '=', 'ftn_joint_account')->get()->first();
        foreach($monthsRequiringTicketResto as $monthRequiringTicketResto) {
            $lastDayOfMonth = Carbon::createFromFormat('m/Y', $monthRequiringTicketResto)->lastOfMonth()->format('Y-m-d');
            DB::table('bank_transaction')->insert([
                'id' => Str::uuid()->toString(),
                'label' => self::DEFAULT_TR_LABEL,
                'debit' => self::DEFAULT_TR_DEBIT,
                'operation_date' => $lastDayOfMonth,
                'value_date' => $lastDayOfMonth,
                'bank_account_id' => $jointAccount->id,
            ]);
            $this->info('Inserted the tickets restaurants transaction for the month : ' . $monthRequiringTicketResto);
        }
    }
}
