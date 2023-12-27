<?php

namespace App\Repository\Bank;

use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    private const START_DATE = '2020-01-01';
    private string $table = "bank_transaction";

    /**
     * getMonthlySpendingsPerCategory
     *
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlySpendingsPerCategory(DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        return DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_parent_category.name AS category',
                'bank_parent_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'))
            ->where('bank_category.name', '!=', 'Voyages')
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('category')
            ->get();
    }

    /**
     * getSpecificMonthlySpendingsPerCategory
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @param  int $threshold
     * @return Collection
     */
    public function getSpecificMonthlySpendingsPerCategory(
        array $excludedCategories,
         DateTime $startDate = new DateTime(self::START_DATE)
         ): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_parent_category.name AS category',
                'bank_parent_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'));
            if (!empty($excludedCategories)) {
                $query->whereNotIn('bank_category.name', $excludedCategories);
            }
        $query
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('category');

        return $query->get();
    }

    /**
     * getSpecificMonthlySpendingsPerSubCategory
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @param  int $threshold
     * @return Collection
     */
    public function getSpecificMonthlySpendingsPerSubCategory(
        array $excludedCategories,
         DateTime $startDate = new DateTime(self::START_DATE)
         ): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'));
            if (!empty($excludedCategories)) {
                $query->whereNotIn('bank_category.name', $excludedCategories);
            }
        $query
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('category');

        return $query->get();
    }

    /**
     * getMonthlySpendingsPerSubCategory
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlySpendingsPerSubCategory(DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        return DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'))
            ->groupBy('period', 'category', 'color', 'bank_parent_category.name')
            ->orderBy('period')
            ->orderBy('bank_parent_category.name')
            ->get();
    }

    /**
     * getYearlySpendingsPerSubCategory
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getYearlySpendingsPerSubCategory(DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        return DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("YEAR(operation_date) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'))
            ->where('bank_category.name', '!=', 'Voyages')
            ->groupBy('period', 'category', 'color', 'bank_parent_category.name')
            ->orderBy('period')
            ->orderBy('bank_parent_category.name')
            ->get();
    }

    /**
     * getYearlySpendingsPerCategory
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getYearlySpendingsPerCategory(DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        return DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("YEAR(operation_date) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_parent_category.name AS category',
                'bank_parent_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'))
            ->where('bank_category.name', '!=', 'Voyages')
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('category')
            ->get();
    }

    /**
     * getMonthlyCategoryRanking
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlyCategoryRanking(DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        return DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_parent_category.name AS category',
                'bank_parent_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'))
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('total_debit', 'DESC')
            ->get();
    }

    /**
     * getYearlyCategoryRanking
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getYearlyCategoryRanking(array $excludedCategories, DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CAST(YEAR(operation_date) AS CHAR) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_parent_category.name AS category',
                'bank_parent_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'));

        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }
        return $query
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('total_debit', 'DESC')
            ->get();
    }

    /**
     * getYearlySubCategoryRanking
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getYearlySubCategoryRanking(array $excludedCategories, DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CAST(YEAR(operation_date) AS CHAR) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'));

        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }
        return $query
            ->groupBy('period', 'category', 'color')
            ->orderBy('period')
            ->orderBy('total_debit', 'DESC')
            ->get();
    }

    /**
     * getMonthlyCategoryRanking
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlySubCategoryRanking(DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        return DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->join('bank_parent_category', 'bank_parent_category.id', '=', 'bank_category.bank_parent_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
                'bank_category.name AS category',
                'bank_category.color AS color',
                'bank_parent_category.name',
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'))
            ->groupBy('period', 'category', 'color', 'bank_parent_category.name')
            ->orderBy('period')
            ->orderBy('total_debit', 'DESC')
            ->get();
    }

    /**
     * getMonthlySpendings
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlySpendings(array $excludedCategories, DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(debit) AS total_debit"),
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'));

        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }

        return $query
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    /**
     * getMonthlyIncomes
     *
     * @param  array $excludedCategories
     * @param  DateTime $startDate
     * @return Collection
     */
    public function getMonthlyIncomes(array $excludedCategories, DateTime $startDate = new DateTime(self::START_DATE)): Collection {
        $query = DB::table($this->table)
            ->join('bank_category', 'bank_category.id', '=', 'bank_transaction.bank_category_id')
            ->select(
                DB::raw("CONCAT(YEAR(operation_date), '-', LPAD(MONTH(operation_date), 2, '0')) AS period"),
                DB::raw("SUM(credit) AS total_credit"),
            )
            ->where('operation_date', '>=', $startDate->format('Y-m-d'));

        if (!empty($excludedCategories)) {
            $query->whereNotIn('bank_category.name', $excludedCategories);
        }

        return $query
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

}
