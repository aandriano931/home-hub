<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

class MacroBudgetParentCategoryPieChart extends ChartWidget
{
    protected static ?string $heading = 'Part des dépenses totales par catégorie';
    protected static ?string $pollingInterval = null;
    private array $chartData = [];
    private array $totalLabels = [];
    private array $totalColors = [];

    protected function getData(): array
    {
        $result = $this->getTotalSpendingsPerCategory();
        $this->setChartData($result);
    
        return [
            'datasets' => [
                [
                    'label' => 'Dépenses totales par catégorie',
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
    
    private function getTotalSpendingsPerCategory(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendings(['Voyages', 'Virements internes']);
        
        return $transactionService->addTotalAndPercentage($results);
    }

    private function setChartData(array $data): void
    {
        ksort($data['parent_categories']);
        foreach ($data['parent_categories'] as $key => $row) {
            $chartData[] = $row['percentage'];
            $totalLabels[] = $key;
            $totalColors[] = $row['color'];
        }
        $this->chartData = $chartData;
        $this->totalLabels = $totalLabels;
        $this->totalColors = $totalColors;
    }

}
