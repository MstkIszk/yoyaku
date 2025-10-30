<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RCheckbox extends Component
{
    public $name;
    public $caption;
    public $selindex;
    public $class;
    public $attributes;
    

    /**
     * Create a new component instance.
     *
     * @param string $name フィールド名
     * @param string $caption ラベル表示名
     * @param integer $selindex 選択されたIndex
     * @param string $class 追加のCSSクラス
     * @param string $attributes 追加の属性
     */
    public function __construct($name, $caption="", $class = '' ,$selindex=-1,$attributes="")
    {
        $this->name = $name;
        $this->caption = $caption;
        $this->$selindex = $selindex;
        $this->class = "InputBox_select " . $class; 
        $this->$attributes = $attributes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.r-checkbox');
    }
}
