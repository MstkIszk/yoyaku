<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleTitle extends Component
{
    public $caption;
    /**
     * Create a new component instance.
     */
    public function __construct($caption)
    {
        $this->caption=$caption;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.article-title');
    }
}
