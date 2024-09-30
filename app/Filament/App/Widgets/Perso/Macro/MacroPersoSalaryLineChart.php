<?php

namespace App\Filament\App\Widgets\Perso\Macro;

use App\Filament\App\Widgets\Perso\AbstractPersoLineChart;
use App\Models\Bank\Account;
use App\Repository\Bank\TransactionRepository;

class MacroPersoSalaryLineChart extends AbstractPersoLineChart
{
    private const SALARIES_COLOR = '#34eb3a';
    private const SALARIES_CATEGORY_ID = 'b19436b0-95cb-11ee-b9e0-0242ac130003';
    protected int | string | array $columnSpan = 'full';
    private array $chartLabels = [];
    private array $salariesData = [];
    protected static ?string $heading = 'Salaires';

    protected function getData(): array
    {
        $this->getSalaries();

        return [
            'datasets' => [
                [
                    'label' => 'Salaires',
                    'data' => $this->salariesData,
                    'borderColor' => self::SALARIES_COLOR,
                    'backgroundColor' => self::SALARIES_COLOR,
                    'pointBackgroundColor' => self::SALARIES_COLOR,
                    'pointRadius' => 2,
                ],
            ],
            'labels' => $this->chartLabels,
        ];
    }
   
    private function getSalaries(): void
    {
        $transactionRepository = new TransactionRepository();
        $salaries = $transactionRepository->getMonthlyIncomesForCategoryId([], Account::PERSO_ACCOUNT_ALIAS, null, self::SALARIES_CATEGORY_ID);
        foreach ($salaries as $salary) {
            $chartLabels[] = $salary->period;
            $salariesData[] = (float) $salary->total_credit;
        }
        $this->chartLabels = $chartLabels;
        $this->salariesData = $salariesData;
    }

}
