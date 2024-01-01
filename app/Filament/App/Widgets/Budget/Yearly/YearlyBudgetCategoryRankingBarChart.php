<?php

namespace App\Filament\App\Widgets\Budget\Yearly;

use App\Filament\App\Widgets\Budget\AbstractBudgetRankingBarChart;

final class YearlyBudgetCategoryRankingBarChart extends AbstractBudgetRankingBarChart
{
    private const NUMBER_TO_KEEP = 10;
    protected static ?string $heading = 'Classement annuel des dépenses par sous-catégorie';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $yearlyData = $this->getYearlySpendings();
        $this->getChartLabels($yearlyData);
        if ($this->filter === null) {
            $this->filter = end($this->chartLabels);
        }
        $activeFilter = $this->filter;
        $chartData = $this->getChartData($yearlyData, $activeFilter);
    
        return [
            'datasets' => [
                [
                    'data' => $chartData['data'],
                    'backgroundColor' => $chartData['colors'],
                    'borderColor' => $chartData['colors'],
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }
    
    protected function getFilters(): ?array
    {
        foreach ($this->chartLabels as $label) {
            $filters[$label] = $label;
        }

        return $filters;
    }

    private function getChartData(array $data, ?string $filter): array
    {
        $chartData = [];
        if (!is_null($filter)) {
            $rawData = $data[$filter];
            usort($rawData['categories'], function ($a, $b) {
                return $b['total'] <=> $a['total'];
            });
            $topCategories = array_slice($rawData['categories'], 0, self::NUMBER_TO_KEEP);
            foreach ($topCategories as $row) {
                $chartData['data'][] = $row['total'];
                $chartData['labels'][] = $row['label'];
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }
}
