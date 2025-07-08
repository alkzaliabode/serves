<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;
    public $newItem = '';
    public $items = ['مهمة 1', 'مهمة 2']; // مثال على قائمة

    public function increment()
    {
        $this->count++;
    }

    public function addItem()
    {
        if (!empty($this->newItem)) {
            $this->items[] = $this->newItem;
            $this->newItem = ''; // مسح حقل الإدخال بعد الإضافة
        }
    }

    public function render()
    {
        return view('livewire.counter');
    }
}