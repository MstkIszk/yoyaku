<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $ShopInf;

    /**
     * Create a new component instance.
     *
     * @param  string  $size
     * @param  string  $color
     * @return void
     */
    public function __construct($ShopInf = null)
    {
        $this->ShopInf = $ShopInf;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
