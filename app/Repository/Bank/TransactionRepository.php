<?php

namespace App\Repository\Bank;

use App\Models\Bank\Account;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public const DEFAULT_JOIN_ACCOUNT_START_DATE = '2020-01-01';
    public const DEFAULT_PERSO_ACCOUNT_START_DATE = '2022-06-01';

    private string $table = "bank_transaction";

    /**
     * getYearlySpendings
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getYearlySpendings(array $excludedCategories, string $accountAlias, DateTime $startDate = null): Collection {
        $query = DB::table($this->table)
            ->join('bank_category',  'bank_category.id',  '=',  'bank_transaction.bank_category_id')
            ->join( 'bank_parent_category',  'bank_parent_category.id', '=',  'bank_category.bank_parent_category_id')
            ->join('bank_account', 'bank_account.id', '=', 'bank_transaction.bank_account_id')
            ->select(
                DB::raw("CAST(YEAR(operation_date) AS CHAR) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name AS parent_category',
                'bank_parent_category.color AS parent_color',
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate, $accountAlias))
            ->where('bank_account.alias', '=', $accountAlias)
            ;
        
        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }
        
        return $query
            ->groupBy('period', 'category', 'color', 'parent_category', 'parent_color')
            ->orderBy('period')
            ->orderBy('parent_category')
            ->get();
    }

    /**
     * getMonthlySpendings
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlySpendings(array $excludedCategories, string $accountAlias, DateTime $startDate = null): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->join('bank_account', 'bank_account.id', '=', 'bank_transaction.bank_account_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name AS parent_category',
                'bank_parent_category.color AS parent_color',
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate, $accountAlias))
            ->where('bank_account.alias', '=', $accountAlias)
            ;
        
        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }

        return $query
            ->groupBy('period', 'category', 'color', 'parent_category', 'parent_color')
            ->orderBy('period')
            ->orderBy('parent_category')
            ->get();
    }

    /**
     * getMonthlyIncomes
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlyIncomes(array $excludedCategories, string $accountAlias, ?DateTime $startDate = null): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_account', 'bank_account.id', '=', 'bank_transaction.bank_account_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(credit) AS total_credit"),
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate, $accountAlias))
            ->where('bank_account.alias', '=', $accountAlias)
            ;

        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }

        return $query
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    public function getMonthlyIncomesForCategoryId(array $excludedCategories, string $accountAlias, ?DateTime $startDate = null, ?string $categoryId = null): Collection {
        $periods = $this->getMonthlyPeriods($startDate, $accountAlias);
        $periodsTable = DB::table(DB::raw('(SELECT "' . implode('" AS period UNION SELECT "', $periods) . '" AS period) AS periods'));
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_account', 'bank_account.id', '=', 'bank_transaction.bank_account_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(credit) AS total_credit"),
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate, $accountAlias))
            ->where('bank_account.alias', '=', $accountAlias);
    
        if (!empty($categoryId)) {
            $query->where('bank_category.id', '=', $categoryId);
        }
    
        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }

        $query->groupBy(DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0'))"));
    
        $result = $periodsTable
            ->leftJoinSub($query, 'transactions', 'periods.period', '=', 'transactions.period')
            ->select(
                'periods.period',
                DB::raw('COALESCE(transactions.total_credit, 0.0) AS total_credit')
            )
            ->orderBy('period')
            ->get();
    
        return $result;
    }


    private function getStartDateString(?DateTime $startDate, string $accountAlias): string
    {
        if (is_null($startDate)) {
            $startDate = new DateTime(
                $accountAlias === Account::PERSO_ACCOUNT_ALIAS
                ? self::DEFAULT_PERSO_ACCOUNT_START_DATE
                : self::DEFAULT_JOIN_ACCOUNT_START_DATE
            );
        }

        return $startDate->format('Y-m-d');
    }

    private function getMonthlyPeriods(?DateTime $startDate, string $accountAlias): array
    {
        if (is_null($startDate)) {
            $startDate = new DateTime(
                $accountAlias === Account::PERSO_ACCOUNT_ALIAS
                ? self::DEFAULT_PERSO_ACCOUNT_START_DATE
                : self::DEFAULT_JOIN_ACCOUNT_START_DATE
            );
        }
        $endDate = new DateTime('last day of this month');
        $periods = [];
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $periods[] = $currentDate->format('Y-m');
            $currentDate->modify('+1 month');
        }

        return $periods;
    }

}
