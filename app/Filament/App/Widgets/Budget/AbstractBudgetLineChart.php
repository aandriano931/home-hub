<?php

namespace App\Filament\App\Widgets\Budget;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

abstract class AbstractBudgetLineChart extends ChartWidget
{
    protected const MOVING_AVERAGE_WINDOW = 6;
    protected int | string | array $columnSpan = 'full';

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

    protected function getChartLabels(): array
    {
        $data = $this->rawData;
        $chartLabels = [];
        foreach ($data as $key => $row) {
            $chartLabels[] = $key;
        }

        return $chartLabels;
    }

    protected function getMovingAverages(array $data): array
    {
        $sum = 0;
        for ($i = 0; $i < self::MOVING_AVERAGE_WINDOW; $i++) {
            $sum += $data[$i];
            $movingAverages[] = $sum / ($i + 1);
        }
        for ($i = self::MOVING_AVERAGE_WINDOW; $i < count($data); $i++) {
            $sum = $sum - $data[$i - self::MOVING_AVERAGE_WINDOW] + $data[$i];
            $movingAverages[] = $sum / self::MOVING_AVERAGE_WINDOW;
        }

        return $movingAverages;
    }

}
