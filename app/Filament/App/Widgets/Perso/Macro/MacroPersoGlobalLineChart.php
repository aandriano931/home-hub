<?php

namespace App\Filament\App\Widgets\Perso\Macro;

use App\Filament\App\Widgets\Perso\AbstractPersoLineChart;
use App\Models\Bank\Account;
use App\Repository\Bank\TransactionRepository;
use App\Services\Bank\TransactionWidgetService;

class MacroPersoGlobalLineChart extends AbstractPersoLineChart
{
    protected const MOVING_AVERAGE_WINDOW = 6;
    private const SPENDINGS_COLOR = '#8D0C04';
    private const INCOMES_COLOR = '#2ECF53';
    private const AVERAGE_SPENDING_COLOR = '#083BCE';
    private const BASE_HEADING_LABEL = 'Apports, dépenses mensuelles et moyenne glissante sur ' . self::MOVING_AVERAGE_WINDOW . ' mois';
    protected int | string | array $columnSpan = 'full';
    private array $spendingsData = [];
    private array $chartLabels = [];
    private array $incomesData = [];
    public bool $isWithoutTravels = false;

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
                    'pointRadius' => 2,
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
                    'pointRadius' => 2,
                ],
                [
                    'label' => 'Moyenne glissante des dépenses',
                    'data' => $this->getMovingAverages($this->spendingsData),
                    'borderColor' => self::AVERAGE_SPENDING_COLOR,
                    'backgroundColor' => self::AVERAGE_SPENDING_COLOR,
                    'pointBackgroundColor' => self::AVERAGE_SPENDING_COLOR,
                    'pointRadius' => 2,
                ],
            ],
            'labels' => $this->chartLabels,
        ];
    }

    private function getTotalSpendings(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        if ($this->isWithoutTravels) {
            $spendings = $transactionRepository->getMonthlySpendings(['Virements internes', 'Voyages'], Account::PERSO_ACCOUNT_ALIAS);
            self::$heading = self::BASE_HEADING_LABEL . ' (hors voyages)';
        } else {
            $spendings = $transactionRepository->getMonthlySpendings(['Virements internes'], Account::PERSO_ACCOUNT_ALIAS);
            self::$heading = self::BASE_HEADING_LABEL;
        }
        $improvedSpendings = $transactionService->addPeriodTotalAndPercentage($spendings);
        array_pop($improvedSpendings);
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
        $incomes = $transactionRepository->getMonthlyIncomes([], Account::PERSO_ACCOUNT_ALIAS);
        foreach ($incomes as $income) {
            $incomesData[] = (float) $income->total_credit;
        }
        $this->incomesData = $incomesData;
    }

}
