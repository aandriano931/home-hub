<?php

namespace App\Filament\App\Widgets\Budget;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use App\Services\Bank\TransactionWidgetService;

class MonthlyBudgetParentCategoryPieChart extends ChartWidget
{
    protected static ?string $heading = 'Dépenses mensuelles par catégorie';
    protected static ?string $pollingInterval = null;
    private array $rawData = [];
    private array $pieLabels = [];
    private array $monthlyData = [];
    private array $monthlyLabels = [];
    private array $monthlyColors = [];

    protected function getData(): array
    {
        $this->getMonthlySpendingsPerParentCategoryAndMonth();
        $this->getPieLabels();
        if ($this->filter === null) {
            $this->filter = end($this->pieLabels);
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
        foreach ($this->pieLabels as $label) {
            $date = Carbon::createFromFormat('Y-m', $label);
            $formattedDate = $date->isoFormat('MMMM YYYY');
            $filters[$label] = ucfirst(trans($formattedDate));
        }

        return $filters;
    }

    private function getMonthlySpendingsPerParentCategoryAndMonth(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendingsPerCategory(['Voyages', 'Virements internes']);
        // dd($results);
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
            foreach ($monthlyRawData['parent_categories'] as $label => $row) {
                $monthlyData[] = $row['percentage'];
                $monthlyLabels[] = $label;
                $monthlyColors[] = $row['color'];
            }
        }
        $this->monthlyData = $monthlyData;
        $this->monthlyLabels = $monthlyLabels;
        $this->monthlyColors = $monthlyColors;
    }

}
