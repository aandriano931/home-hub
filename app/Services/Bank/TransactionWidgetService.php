<?php

namespace App\Services\Bank;

use Illuminate\Support\Collection;

class TransactionWidgetService
{

    public function sortPerMonthAndCategory(Collection $transactionsCollection): array {
        $sortedTransactionsCollection = [];
        foreach ($transactionsCollection as $result) {
            $period = $result->period;
            if (!isset($sortedTransactionsCollection[$period])) {
                $sortedTransactionsCollection[$period] = [
                    'categories' => [],
                ];
            }
            $sortedTransactionsCollection[$period]['categories'][$result->category] = [
                'total' => $result->total_debit,
                'color' => $result->color,
            ];
        }

        return $sortedTransactionsCollection;
    }

    public function addPeriodTotalAndPercentage(Collection $transactionsCollection): array {
        $improvedTransactionsCollection = [];
        foreach ($transactionsCollection as $result) {
            $period = $result->period;
            if (!isset($improvedTransactionsCollection[$period])) {
                $improvedTransactionsCollection[$period] = [
                    'cumulated_total' => 0,
                    'categories' => [],
                ];
            }
            $improvedTransactionsCollection[$period]['cumulated_total'] += $result->total_debit;
            $improvedTransactionsCollection[$period]['categories'][] = [
                'total' => $result->total_debit,
                'label' => $result->category,
                'color' => $result->color,
            ];
        }
        foreach ($improvedTransactionsCollection as &$result) {
            foreach ($result['categories'] as &$category) {
                $category['percentage'] = round(($category['total'] / $result['cumulated_total']) * 100, 2);
            }
        }

        return $improvedTransactionsCollection;
    }

    public function groupPerCategory(Collection $transactionsCollection): array {
        $sortedTransactionsCollection = [];
        foreach ($transactionsCollection as $result) {
            $category = $result->category;
            if (!isset($sortedTransactionsCollection[$category])) {
                $sortedTransactionsCollection[$category] = [
                    'color' => $result->color,
                    'periods' => [],
                ];
            }
            $sortedTransactionsCollection[$category]['periods'][$result->period] = [
                'total' => $result->total_debit,
            ];
        }

        return $sortedTransactionsCollection;
    }
}
