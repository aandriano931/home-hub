<?php

namespace App\Filament\App\Widgets\Budget\Monthly;

use App\Filament\App\Widgets\Budget\AbstractBudgetRankingBarChart;
use Illuminate\Support\Carbon;

class MonthlyBudgetParentCategoryRankingBarChart extends AbstractBudgetRankingBarChart
{
    protected static ?string $heading = 'Classement mensuel des dépenses par catégorie';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $monthlyData = $this->getMonthlySpendings();
        $this->getChartLabels($monthlyData);
        if ($this->filter === null) {
            $this->filter = end($this->chartLabels);
        }
        $activeFilter = $this->filter;
        $chartData = $this->getChartData($monthlyData, $activeFilter);
    
        return [
            'datasets' => [
                [
                    'data' => $chartData['data'],
                    'backgroundColor' => $chartData['colors'],
                    'borderColor' =>$chartData['colors'],
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    
    protected function getFilters(): ?array
    {
        $filters = [];
        foreach ($this->chartLabels as $label) {
            $date = Carbon::createFromFormat('Y-m', $label);
            $formattedDate = $date->isoFormat('MMMM YYYY');
            $filters[$label] = ucfirst(trans($formattedDate));
        }

        return $filters;
    }

    private function getChartData(array $data, ?string $filter): array
    {
        if (!is_null($filter)) {
            $monthlyRawData = $data[$filter];
            uasort($monthlyRawData['parent_categories'], function ($a, $b) {
                return $b['total'] <=> $a['total'];
            });
            foreach ($monthlyRawData['parent_categories'] as $label => $row) {
                $chartData['data'][] = $row['total'];
                $chartData['labels'][] = $label;
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }

}
