<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopClosedModalController extends Controller
{
    public function modal()
    
    {
        return view('livewire.shop-closed-modal');
    }
}
