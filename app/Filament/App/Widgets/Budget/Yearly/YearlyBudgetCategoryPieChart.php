<?php

namespace App\Filament\App\Widgets\Budget\Yearly;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use App\Services\Bank\TransactionWidgetService;

class YearlyBudgetCategoryPieChart extends ChartWidget
{
    protected static ?string $heading = 'Dépenses annuelles par sous-catégorie';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $chartLabels = [];
    private array $yearlyData = [];
    private array $yearlyLabels = [];
    private array $yearlyColors = [];

    protected function getData(): array
    {
        $this->getYearlySpendingsPerSubCategoryAndYear();
        $this->setChartLabels();
        if ($this->filter === null) {
            $this->filter = end($this->chartLabels);
        }
        $activeFilter = $this->filter;
        $this->setChartData($activeFilter);
    
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
        foreach ($this->chartLabels as $label) {
            $date = Carbon::createFromFormat('Y', $label);
            $formattedDate = $date->isoFormat('YYYY');
            $filters[$label] = $formattedDate;
        }

        return $filters;
    }

    private function getYearlySpendingsPerSubCategoryAndYear(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getYearlySpendings(['Voyages', 'Virements internes']);
        $improvedResults = $transactionService->addPeriodTotalAndPercentage($results);
        array_pop($improvedResults);
        $this->rawData = $improvedResults;
    }

    private function setChartLabels(): void
    {
        $data = $this->rawData;
        $chartLabels = [];
        foreach ($data as $key => $row) {
            $chartLabels[] = $key;
        }
        $this->chartLabels = $chartLabels;
    }

    private function setChartData(?string $year): void
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
