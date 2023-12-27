<?php

namespace App\Filament\App\Widgets\Budget;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

class YearlyBudgetCategoryRankingBarChart extends ChartWidget
{
    private const NUMBER_TO_KEEP = 10;
    protected static ?string $heading = 'Classement annuel des dépenses par sous-catégorie';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $pieLabels = [];
    private array $yearlyData = [];
    private array $yearlyLabels = [];
    private array $yearlyColors = [];

    protected function getData(): array
    {
        $this->getYearlyCategoryRanking();
        $this->getPieLabels();
        if ($this->filter === null) {
            $this->filter = end($this->pieLabels);
        }
        $activeFilter = $this->filter;
        $this->getDataForYear($activeFilter);
    
        return [
            'datasets' => [
                [
                    'data' => $this->yearlyData,
                    'backgroundColor' => $this->yearlyColors,
                    'borderColor' => $this->yearlyColors,
                ],
            ],
            'labels' => $this->yearlyLabels,
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
    
    protected function getFilters(): ?array
    {
        foreach ($this->pieLabels as $label) {
            $filters[$label] = $label;
        }

        return $filters;
    }

    private function getYearlyCategoryRanking(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getYearlySubCategoryRanking(['Voyages']);
        $improvedResults = $transactionService->addPeriodTotalAndPercentage($results);
        array_pop($improvedResults);
        $this->rawData = $improvedResults;
    }

    private function getPieLabels(): void
    {
        $data = $this->rawData;
        $pieLabels = [];
        foreach ($data as $key => $row) {
            $pieLabels[] = $key;
        }
        $this->pieLabels = $pieLabels;
    }

    private function getDataForYear(?string $year): void
    {
        $yearlyData = $yearlyLabels = [];
        if (!is_null($year)) {
            $yearlyRawData = $this->rawData[$year];
            usort($yearlyRawData['categories'], function ($a, $b) {
                return $b['total'] <=> $a['total'];
            });
            $topCategories = array_slice($yearlyRawData['categories'], 0, self::NUMBER_TO_KEEP);
            foreach ($topCategories as $row) {
                $yearlyData[] = $row['total'];
                $yearlyLabels[] = $row['label'];
                $yearlyColors[] = $row['color'];
            }
        }
        $this->yearlyData = $yearlyData;
        $this->yearlyLabels = $yearlyLabels;
        $this->yearlyColors = $yearlyColors;
    }

}
