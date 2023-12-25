<?php

namespace App\Services\Bank;

use Illuminate\Support\Collection;

class TransactionWidgetService
{
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

}
