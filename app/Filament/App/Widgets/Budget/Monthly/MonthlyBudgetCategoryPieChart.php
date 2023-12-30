<?php

namespace App\Filament\App\Widgets\Budget\Monthly;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use App\Services\Bank\TransactionWidgetService;

class MonthlyBudgetCategoryPieChart extends ChartWidget
{
    protected static ?string $heading = 'Dépenses mensuelles par sous-catégorie';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $chartLabels = [];
    private array $monthlyData = [];
    private array $monthlyLabels = [];
    private array $monthlyColors = [];

    protected function getData(): array
    {
        $this->getMonthlySpendingsPerSubCategoryAndMonth();
        $this->setChartLabels();
        if ($this->filter === null) {
            $this->filter = end($this->chartLabels);
        }
        $activeFilter = $this->filter;
        $this->getDataForMonth($activeFilter);
    
        return [
            'datasets' => [
                [
                    'label' => 'Dépenses mensuelles pour ' . $activeFilter,
                    'data' => $this->monthlyData,
                    'backgroundColor' => $this->monthlyColors,
                ],
            ],
            'labels' => $this->monthlyLabels,
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
            $date = Carbon::createFromFormat('Y-m', $label);
            $formattedDate = $date->isoFormat('MMMM YYYY');
            $filters[$label] = ucfirst(trans($formattedDate));
        }

        return $filters;
    }

    private function getMonthlySpendingsPerSubCategoryAndMonth(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendings(['Voyages', 'Virements internes']);
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
