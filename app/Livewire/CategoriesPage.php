<?php

namespace App\Livewire;

use Livewire\Component;
#[\Livewire\Attributes\Title('Categories - eCommerce')]

class CategoriesPage extends Component
{
    public function render()
    {
        $categories = \App\Models\Category::where('is_active', 1)->get();
        return view('livewire.categories-page', [
            'categories' => $categories,
        ]);
    }
}
