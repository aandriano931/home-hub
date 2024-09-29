<?php

namespace App\Filament\App\Widgets\Budget;

use App\Models\Bank\Account;
use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

abstract class AbstractBudgetRankingBarChart extends ChartWidget
{
    protected const EXCLUDED_CATEGORIES = ['Voyages', 'Virements internes'];
    protected static ?string $pollingInterval = null;
    protected array $chartLabels = [];
    public string $accountAlias = Account::JOIN_ACCOUNT_ALIAS;


    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.formattedValue + '€';
                            }
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {
                                return value + '€';
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.formattedValue + '€';
                                }
                            }
                        },
                    },
                    y: {
                        ticks: {
                            display: true,
                        },
                    },
                },
            }
        JS);
    }
    
    protected function getChartLabels(array $data): void
    {
        $chartLabels = [];
        foreach ($data as $key => $row) {
            $chartLabels[] = $key;
        }
        $this->chartLabels = $chartLabels;
    }

    protected function getMacroSpendings(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $results = $transactionRepository->getMonthlySpendings(self::EXCLUDED_CATEGORIES, Account::JOIN_ACCOUNT_ALIAS);
        
        return $transactionService->addTotalAndPercentage($results);
    }

    protected function getYearlySpendings(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $yearlyResults = $transactionRepository->getYearlySpendings(self::EXCLUDED_CATEGORIES, Account::JOIN_ACCOUNT_ALIAS);
        $improvedYearlyResults = $transactionService->addPeriodTotalAndPercentage($yearlyResults);
        array_pop($improvedYearlyResults);

        return  $improvedYearlyResults;
    }

    protected function getMonthlySpendings(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $monthlyResults = $transactionRepository->getMonthlySpendings(self::EXCLUDED_CATEGORIES, Account::JOIN_ACCOUNT_ALIAS);
        $improvedMonthlyResults = $transactionService->addPeriodTotalAndPercentage($monthlyResults);
        array_pop($improvedMonthlyResults);

        return  $improvedMonthlyResults;
    }

}
