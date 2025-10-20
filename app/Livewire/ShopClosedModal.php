<?php

namespace App\Livewire;

use Livewire\Component;

class ShopClosedModal extends Component
{
    public $showModal = false;

    public function render()
    {
        return view('livewire.shop-closed-modal');
    }

    public function openModal()
    {
        $this->showModal = true;
    }
 
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }   
}
