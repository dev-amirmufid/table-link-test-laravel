<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PieChart extends Component
{
    public string $chartId;
    public string $title;
    public array $labels;
    public array $datasets;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $chartId = 'pieChart',
        string $title = 'Pie Chart',
        array $labels = [],
        array $datasets = []
    ) {
        $this->chartId = $chartId;
        $this->title = $title;
        $this->labels = $labels;
        $this->datasets = $datasets;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.pie-chart');
    }
}
