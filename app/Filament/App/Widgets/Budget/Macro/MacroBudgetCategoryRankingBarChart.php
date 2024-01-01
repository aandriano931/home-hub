<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Filament\App\Widgets\Budget\AbstractBudgetRankingBarChart;

class MacroBudgetCategoryRankingBarChart extends AbstractBudgetRankingBarChart
{
    private const NUMBER_TO_KEEP = 10;
    protected static ?string $heading = 'Classement des dépenses par sous-catégorie (hors voyages)';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $results = $this->getMacroSpendings();
        $chartData = $this->getChartData($results);
    
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
    
    private function getChartData(array $data): array
    {
        $chartData = [];
        usort($data['categories'], function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        $topCategories = array_slice($data['categories'], 0, self::NUMBER_TO_KEEP);
        foreach ($topCategories as $row) {
            $chartData['data'][] = $row['total'];
            $chartData['labels'][] = $row['label'];
            $chartData['colors'][] = $row['color'];
        }

        return $chartData;
    }

}
