<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

class MacroBudgetCategoryPieChart extends ChartWidget
{
    private const SUBCATEGORY_MINIMUM_THRESHOLD = 0.5;
    protected static ?string $heading = 'Part des dépenses totales par sous-catégorie (>=' . self::SUBCATEGORY_MINIMUM_THRESHOLD . '%)';
    protected static ?string $pollingInterval = null;
    private array $chartData = [];
    private array $totalLabels = [];
    private array $totalColors = [];

    protected function getData(): array
    {
        $results = $this->getTotalSpendingsPerSubCategory();
        $this->setChartData($results);
    
        return [
            'datasets' => [
                [
                    'label' => 'Dépenses totales par sous-catégorie',
                    'data' => $this->chartData,
                    'backgroundColor' => $this->totalColors,
                ],
            ],
            'labels' => $this->totalLabels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ' ' + context.formattedValue + '%';
                            }
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            display: false,
                        },
                    },
                    y: {
                        ticks: {
                            display: false,
                        },
                    },
                },
            }
        JS);
    }
    
    private function getTotalSpendingsPerSubCategory(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendings(['Voyages', 'Virements internes']);

        return $transactionService->addTotalAndPercentage($results);
    }

    private function setChartData(array $data): void
    {
        usort($data['categories'], function ($a, $b) {
            return $a['parent_label'] <=> $b['parent_label'];
        });
        ksort($data['parent_categories']);
        $data['categories'] = array_filter($data['categories'], function ($row) {
            return $row['percentage'] >= self::SUBCATEGORY_MINIMUM_THRESHOLD;
        });
        foreach ($data['categories'] as $row) {
            $chartData[] = $row['percentage'];
            $totalLabels[] = $row['label'];
            $totalColors[] = $row['color'];
        }

        $this->chartData = $chartData;
        $this->totalLabels = $totalLabels;
        $this->totalColors = $totalColors;
    }

}
