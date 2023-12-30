<?php

namespace App\Repository\Bank;

use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    private const DEFAULT_START_DATE = '2020-01-01';
    private string $table = "bank_transaction";

    /**
     * getYearlySpendings
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getYearlySpendings(array $excludedCategories, DateTime $startDate = null): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CAST(YEAR(operation_date) AS CHAR) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name AS parent_category',
                'bank_parent_category.color AS parent_color',
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate));
        
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
    public function getMonthlySpendings(array $excludedCategories, DateTime $startDate = null): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name AS parent_category',
                'bank_parent_category.color AS parent_color',
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate));

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
    public function getMonthlyIncomes(array $excludedCategories, ?DateTime $startDate = null): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(credit) AS total_credit"),
            )
            ->where('operation_date', '>=', $this->getStartDateString($startDate));

        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }

        return $query
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getStartDateString(?DateTime $startDate): string
    {
        if (is_null($startDate)) {
            $startDate = new DateTime(self::DEFAULT_START_DATE);
        }

        return $startDate->format('Y-m-d');
    }

}
