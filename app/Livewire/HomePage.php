<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - eCommerce')]

class HomePage extends Component
{
    
    public function render()
    {
        $brands = \App\Models\Brand::where('is_active', 1)->get();
        $categories = \App\Models\Category::where('is_active', 1)->get();
        return view('livewire.home-page',[
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
