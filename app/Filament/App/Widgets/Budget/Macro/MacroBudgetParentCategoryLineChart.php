<?php

namespace App\Filament\App\Widgets\Budget\Macro;

use App\Filament\App\Widgets\Budget\AbstractBudgetLineChart;
use App\Repository\Bank\TransactionRepository;
use App\Services\Bank\TransactionWidgetService;

final class MacroBudgetParentCategoryLineChart extends AbstractBudgetLineChart
{
    private const AVERAGE_CATEGORY_SPENDING_COLOR = '#F60DEB';
    protected static ?string $heading = 'Dépenses par catégorie et moyenne glissante sur ' . self::MOVING_AVERAGE_WINDOW . ' mois.';
    protected int | string | array $columnSpan = 'full';
    private array $chartData = [];
    private string $categoryColor;
    private string $categoryLabel;

    protected function getData(): array
    {
        $this->getSpendings();
        $filters = $this->getFilters();
        if ($this->filter === null) {
            $this->filter = reset($filters);
        }
        $activeFilter = $this->filter;
        $this->getDataForCategory($activeFilter);
            
        return [
            'datasets' => [
                [
                    'label' => $this->categoryLabel,
                    'borderColor' => $this->categoryColor,
                    'backgroundColor' => $this->categoryColor,
                    'pointBackgroundColor' => $this->categoryColor,
                    'data' => $this->chartData,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Moyenne glissante pour la catégorie : ' . $this->categoryLabel,
                    'data' => $this->getMovingAverages($this->chartData),
                    'borderColor' => self::AVERAGE_CATEGORY_SPENDING_COLOR,
                    'backgroundColor' => self::AVERAGE_CATEGORY_SPENDING_COLOR,
                    'pointBackgroundColor' => self::AVERAGE_CATEGORY_SPENDING_COLOR,
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
            foreach ($data['parent_categories'] as $label => $parentCategory) {
                if (in_array($label, $filters, true) === false) {
                    $filters[$label] = $label;
                }
            }
        }
        asort($filters);

        return $filters;
    }

    private function getDataForCategory(?string $parentCategory): void
    {
        $data = $this->rawData;
        $categoryColor = '';
        foreach ($data as $row) {
            if(isset($row['parent_categories'][$parentCategory])) {
                $chartData[] = $row['parent_categories'][$parentCategory]['total'];
                $categoryColor = $row['parent_categories'][$parentCategory]['color'];
            } else {
                $chartData[] = 0;
            }
        }
        $this->chartData = $chartData;
        $this->categoryColor = $categoryColor;
        $this->categoryLabel = $parentCategory;
    }

}
