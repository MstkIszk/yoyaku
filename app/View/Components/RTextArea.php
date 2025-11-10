<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RTextArea extends Component
{
    public $name;
    public $attr;
    public $class;
    public $placeholder;
    public $msgText;
    public $rows; // 行数を保持するプロパティ


    public function __construct($name,$attr='',$placeholder = '入力してください',$class='',$msgText='')
    {
        $this->name = $name;
        $this->class = $class;
        $this->placeholder = $placeholder ;
        $this->msgText = $msgText;
        $this->attr=$attr;

        // テキスト内容の改行文字（LF, CR+LF, CR）を統一し、行数を計算
        $normalizedText = str_replace(["\r\n", "\r"], "\n", $msgText);
        $lineCount = substr_count($normalizedText, "\n") + 1;
        
        // 最低行数は5行、最大行数は20行に制限する（必要に応じて調整してください）
        $this->rows = max(5, min(20, $lineCount));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if($this->attr == 'label') {
            return view('components.r-text-area-label');
        }
        return view('components.r-text-area');
    }
}
