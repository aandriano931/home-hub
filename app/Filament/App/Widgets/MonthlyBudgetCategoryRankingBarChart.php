<?php

namespace App\Filament\App\Widgets;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use App\Services\Bank\TransactionWidgetService;

class MonthlyBudgetCategoryRankingBarChart extends ChartWidget
{
    protected static ?string $heading = 'Classement mensuel des dépenses par sous-catégorie';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $pieLabels = [];
    private array $monthlyData = [];
    private array $monthlyLabels = [];
    private array $monthlyColors = [];

    protected function getData(): array
    {
        $this->getMonthlySubCategoryRanking();
        $this->getPieLabels();
        if ($this->filter === null) {
            $this->filter = end($this->pieLabels);
        }
        $activeFilter = $this->filter;
        $this->getDataForMonth($activeFilter);
    
        return [
            'datasets' => [
                [
                    'data' => $this->monthlyData,
                    'backgroundColor' => $this->monthlyColors,
                ],
            ],
            'labels' => $this->monthlyLabels,
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
                                return context.formattedValue + '%';
                            }
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {
                                return value + '%';
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.formattedValue + '%';
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
        $filters = [];
        foreach ($this->pieLabels as $label) {
            $date = Carbon::createFromFormat('Y-m', $label);
            $formattedDate = $date->isoFormat('MMMM YYYY');
            $filters[$label] = ucfirst(trans($formattedDate));
        }

        return $filters;
    }

    private function getMonthlySubCategoryRanking(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySubCategoryRanking(new \DateTime('2021-01-01'));
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

    private function getDataForMonth(?string $month): void
    {
        $monthlyData = $monthlyLabels = [];
        if (!is_null($month)) {
            $monthlyRawData = $this->rawData[$month];
            foreach ($monthlyRawData['categories'] as $row) {
                $monthlyData[] = $row['percentage'];
                $monthlyLabels[] = $row['label'];
                $monthlyColors[] = $row['color'];
            }
        }
        $this->monthlyData = $monthlyData;
        $this->monthlyLabels = $monthlyLabels;
        $this->monthlyColors = $monthlyColors;
    }

}
