<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class calDate extends Component
{
    public $dateJson;   //　引数で受け取るJson
    public $youbi;      //　曜日
    /**
     * Create a new component instance.
     */
    public function __construct($dateJson,$youbi)
    {
        $this->$dateJson = $dateJson;
        $this->$youbi = $youbi;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cal-date');
    }
}
