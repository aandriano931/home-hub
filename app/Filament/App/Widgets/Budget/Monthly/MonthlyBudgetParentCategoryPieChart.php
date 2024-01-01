<?php

namespace App\Filament\App\Widgets\Budget\Monthly;

use App\Filament\App\Widgets\Budget\AbstractBudgetPieChart;
use Illuminate\Support\Carbon;

class MonthlyBudgetParentCategoryPieChart extends AbstractBudgetPieChart
{
    protected static ?string $heading = 'Dépenses mensuelles par catégorie';
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
                    'label' => 'Dépenses mensuelles pour ' . $activeFilter,
                    'data' => $chartData['data'],
                    'backgroundColor' => $chartData['colors'],
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
            foreach ($monthlyRawData['parent_categories'] as $label => $row) {
                $chartData['data'][] = $row['percentage'];
                $chartData['labels'][] = $label;
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }
}
