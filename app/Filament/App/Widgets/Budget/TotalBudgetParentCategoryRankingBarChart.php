<?php

namespace App\Filament\App\Widgets\Budget;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

class TotalBudgetParentCategoryRankingBarChart extends ChartWidget
{
    protected static ?string $heading = 'Classement des dépenses par catégorie (hors voyages)';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $pieLabels = [];
    private array $totalData = [];
    private array $totalLabels = [];
    private array $totalColors = [];

    protected function getData(): array
    {
        $this->getTotalCategoryRanking();
        $this->getPieLabels();
        $this->getTotalData();
    
        return [
            'datasets' => [
                [
                    'data' => $this->totalData,
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
    
    private function getTotalCategoryRanking(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getTotalCategoryRanking(['Voyages', 'Virements internes']);
        $improvedResults = $transactionService->addTotalAndPercentage($results);
        $this->rawData = $improvedResults;
    }

    private function getPieLabels(): void
    {
        $data = $this->rawData;
        $pieLabels = [];
        foreach ($data['categories'] as $category) {
            $pieLabels[] = $category['label'];
        }
        $this->pieLabels = $pieLabels;
    }

    private function getTotalData(): void
    {
        $totalData = $totalLabels = [];
        $totalRawData = $this->rawData;
        foreach ($totalRawData['categories'] as $row) {
            $totalData[] = $row['total'];
            $totalLabels[] = $row['label'];
            $totalColors[] = $row['color'];
        }
        $this->totalData = $totalData;
        $this->totalLabels = $totalLabels;
        $this->totalColors = $totalColors;
    }

}
