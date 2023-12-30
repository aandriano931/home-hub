<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

class MacroBudgetParentCategoryRankingBarChart extends ChartWidget
{
    protected static ?string $heading = 'Classement des dépenses par catégorie (hors voyages)';
    protected static ?string $pollingInterval = null;
    private array $chartData = [];
    private array $totalLabels = [];
    private array $totalColors = [];

    protected function getData(): array
    {
        $results = $this->getTotalCategoryRanking();
        $this->setChartData($results);
    
        return [
            'datasets' => [
                [
                    'data' => $this->chartData,
                    'backgroundColor' => $this->totalColors,
                    'borderColor' => $this->totalColors,
                ],
            ],
            'labels' => $this->totalLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.formattedValue + '€';
                            }
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {
                                return value + '€';
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.formattedValue + '€';
                                }
                            }
                        },
                    },
                    y: {
                        ticks: {
                            display: true,
                        },
                    },
                },
            }
        JS);
    }
    
    private function getTotalCategoryRanking(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendings(['Voyages', 'Virements internes']);

        return $transactionService->addTotalAndPercentage($results);
    }

    private function setChartData(array $data): void
    {
        uasort($data['parent_categories'], function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        foreach ($data['parent_categories'] as $label => $row) {
            $chartData[] = $row['total'];
            $totalLabels[] = $label;
            $totalColors[] = $row['color'];
        }
        $this->chartData = $chartData;
        $this->totalLabels = $totalLabels;
        $this->totalColors = $totalColors;
    }

}
