<?php

namespace App\Filament\App\Widgets;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use App\Services\Bank\TransactionWidgetService;

class YearlyBudgetParentCategoryPieChart extends ChartWidget
{
    protected static ?string $heading = 'Dépenses annuelles par catégorie en pourcentage';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $pieLabels = [];
    private array $yearlyData = [];
    private array $yearlyLabels = [];
    private array $yearlyColors = [];

    protected function getData(): array
    {
        $this->getYearlySpendingsPerCategoryAndYear();
        $this->getPieLabels();
        if ($this->filter === null) {
            $this->filter = end($this->pieLabels);
        }
        $activeFilter = $this->filter;
        $this->getDataForYear($activeFilter);
    
        return [
            'datasets' => [
                [
                    'label' => 'Dépenses mensuelles pour ' . $activeFilter,
                    'data' => $this->yearlyData,
                    'backgroundColor' => $this->yearlyColors,
                ],
            ],
            'labels' => $this->yearlyLabels,
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
    
    protected function getFilters(): ?array
    {
        $filters = [];
        foreach ($this->pieLabels as $label) {
            $date = Carbon::createFromFormat('Y', $label);
            $formattedDate = $date->isoFormat('YYYY');
            $filters[$label] = $formattedDate;
        }

        return $filters;
    }

    private function getYearlySpendingsPerCategoryAndYear(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getYearlySpendingsPerCategory(new \DateTime('2021-01-01'));
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
            foreach ($yearlyRawData['categories'] as $row) {
                $yearlyData[] = $row['percentage'];
                $yearlyLabels[] = $row['label'];
                $yearlyColors[] = $row['color'];
            }
        }
        $this->yearlyData = $yearlyData;
        $this->yearlyLabels = $yearlyLabels;
        $this->yearlyColors = $yearlyColors;
    }

}
