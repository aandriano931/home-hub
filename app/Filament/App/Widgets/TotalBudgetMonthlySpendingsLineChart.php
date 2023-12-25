<?php

namespace App\Filament\App\Widgets;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TotalBudgetMonthlySpendingsLineChart extends ChartWidget
{
    protected static ?string $heading = 'Dépenses mensuelles (hors voyages) et moyenne glissante sur un an';

    protected function getData(): array
    {

        $transactionRepository = new TransactionRepository();
        $results = $transactionRepository->getMonthlySpendings(['Voyages'], new \DateTime('2021-01-01'));

        // Remove last month
        $results->pop();

        $spendings = $results->map(function ($item) {
            return $item->total_debit;
        });

        $labels = $results->map(function ($item) {
            return ucfirst(trans(Carbon::createFromFormat('Y-m', $item->period)->isoFormat('MMMM Y')));
        });
      
        return [
            'datasets' => [
                [
                    'label' => 'Total des dépenses mensuelles',
                    'borderColor' => '#EB4936',
                    'backgroundColor' => '#EB4936',
                    'data' => $spendings,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Moyenne glissante',
                    'data' => $this->getEvolutiveMonthlyAverage($spendings->all()),
                    'borderColor' => '#9BD0F5',
                    'backgroundColor' => '#9BD0F5',
                    'pointRadius' => 0,
                ],
            ],
            'labels' => $labels,
        ];
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


    private function getEvolutiveMonthlyAverage(array $monthlyValues): array
    {
        $windowSize = 12;
        $sum = 0;
        for ($i = 0; $i < $windowSize; $i++) {
            $sum += $monthlyValues[$i];
            $movingMonthlyAverages[] = $sum / ($i + 1);
        }
        for ($i = $windowSize; $i < count($monthlyValues); $i++) {
            $sum = $sum - $monthlyValues[$i - $windowSize] + $monthlyValues[$i];
            $movingMonthlyAverages[] = $sum / $windowSize;
        }

        return $movingMonthlyAverages;
    }
}