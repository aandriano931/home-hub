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

    public function addTotalAndPercentage(Collection $transactionsCollection): array {
        $improvedTransactionsCollection = [
            'cumulated_total' => 0,
            'categories' => [],
        ];
        foreach ($transactionsCollection as $result) {
            $improvedTransactionsCollection['cumulated_total'] += $result->total_debit;
            $improvedTransactionsCollection['categories'][] = [
                'total' => $result->total_debit,
                'label' => $result->category,
                'color' => $result->color,
                'parent_label' => $result->parent_category,
                'parent_color' => $result->parent_color,
            ];
        }
        foreach ($improvedTransactionsCollection['categories'] as &$category) {
            $category['percentage'] = round(($category['total'] / $improvedTransactionsCollection['cumulated_total']) * 100, 2);
        }

        return $improvedTransactionsCollection;
    }

    public function addPeriodTotalAndPercentage(Collection $transactionsCollection): array {
        $improvedTransactionsCollection = [];
        foreach ($transactionsCollection as $result) {
            $period = $result->period;
            $this->initializePeriodData($improvedTransactionsCollection, $period);
            $improvedTransactionsCollection[$period]['cumulated_total'] += $result->total_debit;
            $this->addCategoryData($improvedTransactionsCollection[$period], $result);
        }
        $this->calculatePercentages($improvedTransactionsCollection);
    
        return $improvedTransactionsCollection;
    }
    
    private function initializePeriodData(array &$collection, string $period): void {
        if (!isset($collection[$period])) {
            $collection[$period] = [
                'cumulated_total' => 0,
                'categories' => [],
                'parent_categories' => [],
            ];
        }
    }
    
    private function addCategoryData(array &$collection, $result): void {
        $categoryData = [
            'total' => $result->total_debit,
            'label' => $result->category,
            'color' => $result->color,
            'parent_label' => $result->parent_category,
            'parent_color' => $result->parent_color,
        ];
        $collection['categories'][] = $categoryData;
        $this->updateParentCategories($collection['parent_categories'], $categoryData);
    }
    
    private function updateParentCategories(array &$parentCategories, array $categoryData): void {
        if (isset($parentCategories[$categoryData['parent_label']])) {
            $parentCategories[$categoryData['parent_label']]['total'] = $parentCategories[$categoryData['parent_label']]['total'] + $categoryData['total'];
        } else {
            $parentCategories[$categoryData['parent_label']] = [
                'total' => (float) $categoryData['total'],
                'color' => $categoryData['parent_color'],
            ];
        }
    }
    
    private function calculatePercentages(array &$collection): void {
        foreach ($collection as &$periodData) {
            foreach ($periodData['categories'] as &$category) {
                $category['percentage'] = round(($category['total'] / $periodData['cumulated_total']) * 100, 2);
                $periodData['parent_categories'][$category['parent_label']]['percentage'] = round(($periodData['parent_categories'][$category['parent_label']]['total'] / $periodData['cumulated_total']) * 100, 2);
            }
        }
    }

}
