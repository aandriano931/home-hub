<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Filament\App\Widgets\Budget\AbstractBudgetPieChart;

final class MacroBudgetCategoryPieChart extends AbstractBudgetPieChart
{
    private const SUBCATEGORY_MINIMUM_THRESHOLD = 0.5;
    protected static ?string $heading = 'Part des dépenses totales par sous-catégorie (>=' . self::SUBCATEGORY_MINIMUM_THRESHOLD . '%)';
    protected static ?string $pollingInterval = null;
    
    protected function getData(): array
    {
        $results = $this->getMacroSpendings();
        $chartData = $this->getChartData($results);
    
        return [
            'datasets' => [
                [
                    'label' => 'Dépenses totales par sous-catégorie',
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
        usort($data['categories'], function ($a, $b) {
            return $a['parent_label'] <=> $b['parent_label'];
        });
        ksort($data['parent_categories']);
        $data['categories'] = array_filter($data['categories'], function ($row) {
            return $row['percentage'] >= self::SUBCATEGORY_MINIMUM_THRESHOLD;
        });
        foreach ($data['categories'] as $row) {
            $chartData['data'][] = $row['percentage'];
            $chartData['labels'][] = $row['label'];
            $chartData['colors'][] = $row['color'];
        }

        return $chartData;
    }

}
