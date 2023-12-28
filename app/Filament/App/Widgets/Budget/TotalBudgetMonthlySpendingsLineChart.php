<?php

namespace App\Filament\App\Widgets\Budget;

use App\Repository\Bank\TransactionRepository;
use App\Services\Bank\TransactionWidgetService;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TotalBudgetMonthlySpendingsLineChart extends ChartWidget
{
    protected static ?string $heading = 'Dépenses mensuelles (hors voyages) et moyenne glissante sur 6 mois';
    protected int | string | array $columnSpan = 'full';
    private array $rawData = [];
    private array $pieLabels = [];
    private array $totalData = [];

    protected function getData(): array
    {
        $this->getTotalSpendings();
        $this->getChartLabels();
        $this->getTotalData();
     
        return [
            'datasets' => [
                [
                    'label' => 'Total des dépenses mensuelles',
                    'borderColor' => '#EB4936',
                    'backgroundColor' => '#EB4936',
                    'data' => $this->totalData,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Moyenne glissante',
                    'data' => $this->getEvolutiveMonthlyAverage($this->totalData, 6),
                    'borderColor' => '#9BD0F5',
                    'backgroundColor' => '#9BD0F5',
                    'pointRadius' => 0,
                ],
            ],
            'labels' => $this->pieLabels,
        ];
    }

    private function getChartLabels(): void
    {
        $data = $this->rawData;
        $pieLabels = [];
        foreach ($data as $key => $row) {
            $pieLabels[] = $key;
        }
        $this->pieLabels = $pieLabels;
    }

    private function getTotalSpendings(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendings(['Voyages', 'Virements internes']);
        $improvedResults = $transactionService->addPeriodTotalAndPercentage($results);
        $this->rawData = $improvedResults;
    }

    private function getTotalData(): void
    {
        $totalRawData = $this->rawData;
        foreach ($totalRawData as $period) {
            $totalData[] = $period['cumulated_total'];
        }
        $this->totalData = $totalData;
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                plugins: {
                    legend: {
                        display: true,
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value, index, values) {
                                return value + '€';
                            }
                        },
                    },
                },
            }
        JS);
    }


    private function getEvolutiveMonthlyAverage(array $monthlyValues, int $window): array
    {
        $sum = 0;
        for ($i = 0; $i < $window; $i++) {
            $sum += $monthlyValues[$i];
            $movingMonthlyAverages[] = $sum / ($i + 1);
        }
        for ($i = $window; $i < count($monthlyValues); $i++) {
            $sum = $sum - $monthlyValues[$i - $window] + $monthlyValues[$i];
            $movingMonthlyAverages[] = $sum / $window;
        }

        return $movingMonthlyAverages;
    }
}
