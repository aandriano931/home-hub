<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Repository\Bank\TransactionRepository;
use App\Services\Bank\TransactionWidgetService;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class MacroBudgetGlobalLineChart extends ChartWidget
{
    private const MOVING_AVERAGE_WINDOW = 6;
    private const SPENDINGS_COLOR = '#8D0C04';
    private const INCOMES_COLOR = '#2ECF53';
    private const AVERAGE_SPENDING_COLOR = '#083BCE';
    protected static ?string $heading = 'Apports, dépenses mensuelles et moyenne glissante sur ' . self::MOVING_AVERAGE_WINDOW . ' mois';
    protected int | string | array $columnSpan = 'full';
    private array $spendingsData = [];
    private array $chartLabels = [];
    private array $incomesData = [];

    protected function getData(): array
    {
        $this->getTotalSpendings();
        $this->getTotalIncomes();
     
        return [
            'datasets' => [
                [
                    'label' => 'Total des dépenses mensuelles',
                    'borderColor' => self::SPENDINGS_COLOR,
                    'backgroundColor' => self::SPENDINGS_COLOR,
                    'pointBackgroundColor' => self::SPENDINGS_COLOR,
                    'data' => $this->spendingsData,
                    'pointRadius' => 0,
                    'fill' => [
                        'target'=> 1,
                        'above' => 'rgb(140, 12, 4, 0.3)',
                        'below' => 'rgb(9, 140, 4, 0.3)',
                    ],
                ],
                [
                    'label' => 'Total des apports mensuels',
                    'data' => $this->incomesData,
                    'borderColor' => self::INCOMES_COLOR,
                    'backgroundColor' => self::INCOMES_COLOR,
                    'pointBackgroundColor' => self::INCOMES_COLOR,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Moyenne glissante des dépenses',
                    'data' => $this->getEvolutiveMonthlyAverage($this->spendingsData, self::MOVING_AVERAGE_WINDOW),
                    'borderColor' => self::AVERAGE_SPENDING_COLOR,
                    'backgroundColor' => self::AVERAGE_SPENDING_COLOR,
                    'pointBackgroundColor' => self::AVERAGE_SPENDING_COLOR,
                    'pointRadius' => 0,
                ],
            ],
            'labels' => $this->chartLabels,
        ];
    }

    private function getTotalSpendings(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $spendings = $transactionRepository->getMonthlySpendings(['Virements internes']);
        $improvedSpendings = $transactionService->addPeriodTotalAndPercentage($spendings);
        foreach ($improvedSpendings as $label => $period) {
            $chartLabels[] = $label;
            $spendingsData[] = $period['cumulated_total'];
        }
        $this->chartLabels = $chartLabels;
        $this->spendingsData = $spendingsData;
    }

    private function getTotalIncomes(): void
    {
        $transactionRepository = new TransactionRepository();
        $incomes = $transactionRepository->getMonthlyIncomes([]);
        foreach ($incomes as $income) {
            $incomesData[] = (float) $income->total_credit;
        }
        $this->incomesData = $incomesData;
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
