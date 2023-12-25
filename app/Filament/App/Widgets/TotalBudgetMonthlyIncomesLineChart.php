<?php

namespace App\Filament\App\Widgets;

use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TotalBudgetMonthlyIncomesLineChart extends ChartWidget
{
    protected static ?string $heading = 'Apports mensuels et moyenne glissante sur un an';

    protected function getData(): array
    {

        $transactionRepository = new TransactionRepository();
        $results = $transactionRepository->getMonthlyIncomes([], new \DateTime('2021-01-01'));

        // Remove last month
        $results->pop();

        $incomes = $results->map(function ($item) {
            return $item->total_credit;
        });

        $labels = $results->map(function ($item) {
            return ucfirst(trans(Carbon::createFromFormat('Y-m', $item->period)->isoFormat('MMMM Y')));
        });
      
        return [
            'datasets' => [
                [
                    'label' => 'Total des apports mensuels',
                    'borderColor' => '#18E346',
                    'backgroundColor' => '#18E346',
                    'data' => $incomes,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Moyenne glissante',
                    'data' => $this->getEvolutiveMonthlyAverage($incomes->all()),
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
