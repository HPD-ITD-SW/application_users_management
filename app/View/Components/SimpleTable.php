<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SimpleTable extends Component
{
    public $title;
    public $description;
    public $rows;

    public function __construct($title, $description, $rows)
    {
        $this->title = $title;
        $this->description = $description;
        $this->rows = $rows;
    }

    public function render()
    {
        return view('components.simple-table');
    }
}
