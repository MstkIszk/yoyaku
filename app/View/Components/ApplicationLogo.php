<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApplicationLogo extends Component
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
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.application-logo');
    }
}

