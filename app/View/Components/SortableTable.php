<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SortableTable extends Component
{
    public $title;
    public $description;
    public $columns;
    public $tableData; // <-- This property must be public

    public function __construct($title = null, $description = null, $columns = [], $tableData = [])
    {
        $this->title = $title;
        $this->description = $description;
        $this->columns = $columns;
        $this->tableData = $tableData; // default to an empty array if not provided
    }

    public function render()
    {
        return view('components.sortable-table');
    }
}
