<?php

namespace App\Filament\App\Widgets\Perso\Monthly;

use App\Filament\App\Widgets\Perso\AbstractPersoRankingBarChart;
use Illuminate\Support\Carbon;

final class MonthlyPersoParentCategoryRankingBarChart extends AbstractPersoRankingBarChart
{
    protected static ?string $heading = 'Classement mensuel des dépenses par catégorie';
    protected static ?string $pollingInterval = null;
    public bool $isInitializedWithPreviousMonth = false;

    protected function getData(): array
    {
        $monthlyData = $this->getMonthlySpendings();
        $this->getChartLabels($monthlyData);
        if ($this->filter === null) {
            $this->filter = $this->isInitializedWithPreviousMonth ? $this->chartLabels[count($this->chartLabels) - 2] : end($this->chartLabels);
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
        $chartData = [];
        if (!is_null($filter)) {
            $rawData = $data[$filter];
            uasort($rawData['parent_categories'], function ($a, $b) {
                return $b['total'] <=> $a['total'];
            });
            foreach ($rawData['parent_categories'] as $label => $row) {
                $chartData['data'][] = $row['total'];
                $chartData['labels'][] = $label;
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }

}
