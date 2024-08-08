<?php

namespace App\Livewire;

use Livewire\Component;

class ProductsPage extends Component
{
    public function render()
    {
        $products = \App\Models\Product::where('is_active', 1)->get();
        return view('livewire.products-page', [
            'products' => $products,
        ]);
    }
}
