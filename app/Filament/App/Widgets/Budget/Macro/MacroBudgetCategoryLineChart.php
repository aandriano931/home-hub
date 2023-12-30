<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Repository\Bank\TransactionRepository;
use App\Services\Bank\TransactionWidgetService;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class MacroBudgetCategoryLineChart extends ChartWidget
{
    private const MOVING_AVERAGE_WINDOW = 12;
    private const AVERAGE_SUBCATEGORY_SPENDING_COLOR = '#F60DEB';
    protected static ?string $heading = 'Dépenses par sous-catégorie et moyenne glissante sur ' . self::MOVING_AVERAGE_WINDOW . ' mois.';
    protected int | string | array $columnSpan = 'full';
    private array $chartData = [];
    private string $subCategoryColor;
    private string $subCategoryLabel;

    protected function getData(): array
    {
        $this->getSpendings();
        $this->setChartLabels();
        $filters = $this->getFilters();
        if ($this->filter === null) {
            $this->filter = reset($filters);
        }
        $activeFilter = $this->filter;
        $this->getDataForSubCategory($activeFilter);
            
        return [
            'datasets' => [
                [
                    'label' => $this->subCategoryLabel,
                    'borderColor' => $this->subCategoryColor,
                    'backgroundColor' => $this->subCategoryColor,
                    'pointBackgroundColor' => $this->subCategoryColor,
                    'data' => $this->chartData,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Moyenne glissante pour la catégorie : ' . $this->subCategoryLabel,
                    'data' => $this->getEvolutiveMonthlyAverage($this->chartData, self::MOVING_AVERAGE_WINDOW),
                    'borderColor' => self::AVERAGE_SUBCATEGORY_SPENDING_COLOR,
                    'backgroundColor' => self::AVERAGE_SUBCATEGORY_SPENDING_COLOR,
                    'pointBackgroundColor' => self::AVERAGE_SUBCATEGORY_SPENDING_COLOR,
                    'pointRadius' => 1,
                ],
            ],
            'labels' => $this->chartLabels,
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

    private function getSpendings(): void
    {
        $transactionRepository = new TransactionRepository();
        $transactionService = new TransactionWidgetService();
        $spendings = $transactionRepository->getMonthlySpendings(['Virements internes']);
        $improvedSpendings = $transactionService->addPeriodTotalAndPercentage($spendings);
        array_pop($improvedSpendings);
        $this->rawData = $improvedSpendings;
    }

    protected function getFilters(): ?array
    {
        $filters = [];
        foreach ($this->rawData as $data) {
            foreach ($data['categories'] as $subCategory) {
                if (in_array($subCategory['label'], $filters, true) === false) {
                    $filters[$subCategory['label']] = $subCategory['label'];
                }
            }
        }
        asort($filters);

        return $filters;
    }

    private function setChartLabels(): void
    {
        $data = $this->rawData;
        $chartLabels = [];
        foreach ($data as $key => $row) {
            $chartLabels[] = $key;
        }
        $this->chartLabels = $chartLabels;
    }

    private function getDataForSubCategory(?string $subCategoryLabel): void
    {
        $data = $this->rawData;
        $subCategoryColor = '';
        foreach ($data as $row) {
            $total = 0;
            foreach ($row['categories'] as $subCategory) {
                if ($subCategory['label'] === $subCategoryLabel) {
                    $total = $subCategory['total'];
                    $subCategoryColor = $subCategory['color'];
                }
            }
            $chartData[] = $total;

        }
        $this->chartData = $chartData;
        $this->subCategoryColor = $subCategoryColor;
        $this->subCategoryLabel = $subCategoryLabel;
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
