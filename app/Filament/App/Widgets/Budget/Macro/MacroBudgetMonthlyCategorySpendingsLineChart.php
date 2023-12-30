<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MacroBudgetMonthlyCategorySpendingsLineChart extends ChartWidget
{
    private const CATEGORY_THRESHOLD = 3;
    protected static ?string $heading = 'Top '. self::CATEGORY_THRESHOLD . ' des dépenses par sous-catégories (hors voyages)';
    protected int | string | array $columnSpan = 'full';

    private array $datasets = [];
    private array $labels = [];

    protected function getData(): array
    {
        $transactionRepository = new TransactionRepository();
        $results = $transactionRepository->getMonthlySpendings(['Voyages', 'Virements internes']);
        $categories = $this->extractCategoriesAndColors($results->all());
        $data = $this->initializeDataWithCategories($categories);
        $this->generateDatasetsAndLabels($results->all(), $data, $categories);
            
        return [
            'datasets' => $this->datasets,
            'labels' => $this->labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                plugins: {
                    legend: {
                        display: true,
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value, index, values) {
                                return value + '€';
                            }
                        },
                    },
                },
            }
        JS);
    }

    private function sortCategoriesAndKeepTheBest(array $categoryDatasets, int $numberToKeep): array
    {
        usort($categoryDatasets, function ($a, $b) {
            $sumA = array_sum($a['data']);
            $sumB = array_sum($b['data']);
            return $sumB <=> $sumA;
        });
        return array_slice($categoryDatasets, 0, $numberToKeep);
    }

    private function extractCategoriesAndColors(array $results): array
    {
        $categories = [];
        foreach ($results as $result) {
            foreach ($result['categories'] as $key => $data) {
                $categories[$key] = $data['color'];
            }
        }

        return $categories;
    }

    private function initializeDataWithCategories(array $categories): array
    {
        foreach ($categories as $label => $color) {
            $data[$label] = [
                'label' => $label,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'data' => [],
                'pointRadius' => 0,
            ];
        }

        return $data;
    }

    private function generateDatasetsAndLabels(array $results, array $sortedData, array $categories): void
    {
        foreach ($results as $month => $data) {
            foreach ($categories as $label => $color) {
                $sortedData[$label]['data'][] = isset($data['categories'][$label]) ? (float) $data['categories'][$label]['total'] : 0;
            }
            $this->labels[] = ucfirst(trans(Carbon::createFromFormat('Y-m', $month)->isoFormat('MMMM Y')));
        }
        $datasets = array_values($sortedData);
        $this->datasets = $this->sortCategoriesAndKeepTheBest($datasets, self::CATEGORY_THRESHOLD);
    }

}
