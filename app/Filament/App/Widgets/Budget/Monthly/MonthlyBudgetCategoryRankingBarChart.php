<?php

namespace App\Filament\App\Widgets\Budget\Monthly;

use App\Filament\App\Widgets\Budget\AbstractBudgetRankingBarChart;
use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Illuminate\Support\Carbon;
use App\Services\Bank\TransactionWidgetService;

class MonthlyBudgetCategoryRankingBarChart extends AbstractBudgetRankingBarChart
{
    private const NUMBER_TO_KEEP = 10;
    protected static ?string $heading = 'Classement mensuel des dépenses par sous-catégorie';
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
            usort($monthlyRawData['categories'], function ($a, $b) {
                return $b['total'] <=> $a['total'];
            });
            $topCategories = array_slice($monthlyRawData['categories'], 0, self::NUMBER_TO_KEEP);
            foreach ($topCategories as $row) {
                $chartData['data'][] = $row['total'];
                $chartData['labels'][] = $row['label'];
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }

}
