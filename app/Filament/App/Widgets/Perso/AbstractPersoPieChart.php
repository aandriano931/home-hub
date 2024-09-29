<?php

namespace App\Filament\App\Widgets\Perso;

use App\Models\Bank\Account;
use App\Repository\Bank\TransactionRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\Bank\TransactionWidgetService;

abstract class AbstractPersoPieChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;
    protected array $chartLabels = [];
    protected const EXCLUDED_CATEGORIES = ['Voyages', 'Virements internes'];

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
        $results = $transactionRepository->getMonthlySpendings(self::EXCLUDED_CATEGORIES, Account::PERSO_ACCOUNT_ALIAS);
        
        return $transactionService->addTotalAndPercentage($results);
    }

    protected function getYearlySpendings(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $yearlyResults = $transactionRepository->getYearlySpendings(self::EXCLUDED_CATEGORIES, Account::PERSO_ACCOUNT_ALIAS);
        $improvedYearlyResults = $transactionService->addPeriodTotalAndPercentage($yearlyResults);
        array_pop($improvedYearlyResults);

        return  $improvedYearlyResults;
    }

    protected function getMonthlySpendings(): array
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $monthlyResults = $transactionRepository->getMonthlySpendings(self::EXCLUDED_CATEGORIES, Account::PERSO_ACCOUNT_ALIAS);
        $improvedMonthlyResults = $transactionService->addPeriodTotalAndPercentage($monthlyResults);
        array_pop($improvedMonthlyResults);

        return  $improvedMonthlyResults;
    }

    
    
}
