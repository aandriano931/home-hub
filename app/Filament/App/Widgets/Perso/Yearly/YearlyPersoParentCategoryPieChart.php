<?php

namespace App\Filament\App\Widgets\Perso\Yearly;

use App\Filament\App\Widgets\Perso\AbstractPersoPieChart;
use App\Models\Bank\Account;
use Illuminate\Support\Carbon;

final class YearlyPersoParentCategoryPieChart extends AbstractPersoPieChart
{
    protected static ?string $heading = 'Dépenses annuelles par catégorie';
    protected static ?string $pollingInterval = null;
    public bool $isInitializedWithPreviousYear = false;

    protected function getData(): array
    {
        $yearlyData = $this->getYearlySpendings();
        $this->getChartLabels($yearlyData);
        if ($this->filter === null) {
            $this->filter = $this->isInitializedWithPreviousYear ? $this->chartLabels[count($this->chartLabels) - 2] : end($this->chartLabels);
        }
        $activeFilter = $this->filter;
        $chartData = $this->getChartData($yearlyData, $activeFilter);

        return [
            'datasets' => [
                [
                    'label' => 'Dépenses annuelles pour ' . $activeFilter,
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
            $date = Carbon::createFromFormat('Y', $label);
            $formattedDate = $date->isoFormat('YYYY');
            $filters[$label] = $formattedDate;
        }

        return $filters;
    }

    private function getChartData(array $data, ?string $year): array
    {
        $chartData = [];
        if (!is_null($year)) {
            $rawData = $data[$year];
            foreach ($rawData['parent_categories'] as $label => $row) {
                $chartData['data'][] = $row['percentage'];
                $chartData['labels'][] = $label;
                $chartData['colors'][] = $row['color'];
            }
        }

        return $chartData;
    }

}
