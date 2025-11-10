<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RTextbox extends Component
{
    public $name;
    public $type;
    public $value;
    public $placeholder;
    public $width;
    public $class;
    public $attr;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $type='text', $width = 10, $value = 'are', $placeholder = '入力してください',$class='',$attr='')
    {
        $this->name=$name;
        $this->type=$type;
        $this->value=$value;
        $this->placeholder=$placeholder;
        $this->width=$width;
        if($type == "label") {
            $this->class="InputBox_Label txtWidth_90pc ". $class;
        }
        else if($type == "checkbox") {
            $this->class="checkbox-line ". $class;
        }
        else {
            $this->class="py-2 ". $class . "border border-gray-300 rounded-md";
        }
        $this->attr=$attr;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if($this->type == "label") {
            /* 表示 : resources\views\components\r-textbox.blade.php */
            return view('components.r-labelbox');
        }
        else if($this->type == "checkbox") {
            return view('components.r-checkboxSingle');
        }
        /* 表示 : resources\views\components\r-textbox.blade.php */
        return view('components.r-textbox');
    }
}
