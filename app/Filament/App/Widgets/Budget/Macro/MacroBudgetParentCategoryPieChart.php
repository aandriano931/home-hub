<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Filament\App\Widgets\Budget\AbstractBudgetPieChart;

class MacroBudgetParentCategoryPieChart extends AbstractBudgetPieChart
{
    protected static ?string $heading = 'Part des dépenses totales par catégorie';
    protected static ?string $pollingInterval = null;
    protected function getData(): array
    {
        $results = $this->getMacroSpendings();
        $chartData = $this->getChartData($results);
    
        return [
            'datasets' => [
                [
                    'label' => 'Dépenses totales par catégorie',
                    'data' => $chartData['data'],
                    'backgroundColor' => $chartData['colors'],
                ],
            ],
            'labels' => $chartData['labels'],
        ];
    }

    private function getChartData(array $data): array
    {
        $chartData = [];
        ksort($data['parent_categories']);
        foreach ($data['parent_categories'] as $key => $row) {
            $chartData['data'][] = $row['percentage'];
            $chartData['labels'][] = $key;
            $chartData['colors'][] = $row['color'];
        }
        
        return $chartData;
    }

}
