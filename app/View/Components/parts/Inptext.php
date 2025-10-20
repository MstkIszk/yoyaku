<?php

namespace App\View\Components\parts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Inptext extends Component
{
    public $name;
    public $type;
    public $value;
    public $placeholder;
    public $width;
    public $class;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $type='text', $width = 10, $value = 'are', $placeholder = '入力してください',$class='')
    {
        $this->name=$name;
        $this->type=$type;
        $this->value=$value;
        $this->placeholder=$placeholder;
        $this->width=$width;
        $this->class="py-2 ". $class . "border border-gray-300 rounded-md";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.parts.inptext');
    }
}
