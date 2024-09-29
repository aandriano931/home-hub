<?php

namespace App\Filament\App\Widgets\Perso\Macro;

use App\Filament\App\Widgets\Perso\AbstractPersoRankingBarChart;

final class MacroPersoParentCategoryRankingBarChart extends AbstractPersoRankingBarChart
{
    protected static ?string $heading = 'Classement des dépenses par catégorie (hors voyages)';
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
        uasort($data['parent_categories'], function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        foreach ($data['parent_categories'] as $key => $row) {
            $chartData['data'][] = $row['total'];
            $chartData['labels'][] = $key;
            $chartData['colors'][] = $row['color'];
        }

        return $chartData;
    }
}
