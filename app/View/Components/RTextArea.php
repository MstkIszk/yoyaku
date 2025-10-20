<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RTextArea extends Component
{
    public $name;
    public $class;
    public $placeholder;
    public $msgText;

    public function __construct($name,$placeholder = '入力してください',$class='',$msgText='')
    {
        $this->name = $name;
        $this->class = $class;
        $this->placeholder = $placeholder ;
        $this->msgText = $msgText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.r-text-area');
    }
}
