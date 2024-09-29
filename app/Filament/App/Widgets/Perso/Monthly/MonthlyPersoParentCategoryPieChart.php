<?php

namespace App\Filament\App\Widgets\Perso\Monthly;

use App\Filament\App\Widgets\Perso\AbstractPersoPieChart;
use App\Models\Bank\Account;
use Illuminate\Support\Carbon;

final class MonthlyPersoParentCategoryPieChart extends AbstractPersoPieChart
{
    protected static ?string $heading = 'Dépenses mensuelles par catégorie';
    protected static ?string $pollingInterval = null;
    public bool $isInitializedWithPreviousMonth = false;

    protected function getData(): array
    {
        $monthlyData = $this->getMonthlySpendings();
        $this->getChartLabels($monthlyData);
        if ($this->filter === null) {
            $this->filter = $this->isInitializedWithPreviousMonth && !empty($this->chartLabels) ? $this->chartLabels[count($this->chartLabels) - 2] : end($this->chartLabels);
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
        $chartData = [];
        if (!is_null($filter) && !empty($data)) {
            $rawData = $data[$filter];
            foreach ($rawData['parent_categories'] as $label => $row) {
                $chartData['data'][] = $row['percentage'];
                $chartData['labels'][] = $label;
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }
}
