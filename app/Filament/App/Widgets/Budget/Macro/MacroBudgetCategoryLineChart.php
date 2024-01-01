<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Filament\App\Widgets\Budget\AbstractBudgetLineChart;
use App\Repository\Bank\TransactionRepository;
use App\Services\Bank\TransactionWidgetService;

final class MacroBudgetCategoryLineChart extends AbstractBudgetLineChart
{
    private const AVERAGE_SUBCATEGORY_SPENDING_COLOR = '#F60DEB';
    protected static ?string $heading = 'Dépenses par sous-catégorie et moyenne glissante sur ' . self::MOVING_AVERAGE_WINDOW . ' mois.';
    protected int | string | array $columnSpan = 'full';
    private array $chartData = [];
    private string $subCategoryColor;
    private string $subCategoryLabel;

    protected function getData(): array
    {
        $this->getSpendings();
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
                    'label' => 'Moyenne glissante pour la sous-catégorie : ' . $this->subCategoryLabel,
                    'data' => $this->getMovingAverages($this->chartData),
                    'borderColor' => self::AVERAGE_SUBCATEGORY_SPENDING_COLOR,
                    'backgroundColor' => self::AVERAGE_SUBCATEGORY_SPENDING_COLOR,
                    'pointBackgroundColor' => self::AVERAGE_SUBCATEGORY_SPENDING_COLOR,
                    'pointRadius' => 1,
                ],
            ],
            'labels' => $this->getChartLabels(),
        ];
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

}
