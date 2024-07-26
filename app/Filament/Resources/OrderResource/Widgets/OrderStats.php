<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', \App\Models\Order::query()->where('status', 'new')->count() ),
            Stat::make('Order Processing', \App\Models\Order::query()->where('status', 'processing')->count() ),
            Stat::make('Order Delivered', \App\Models\Order::query()->where('status', 'delivered')->count() ),
            // Stat::make('Order Shipped', \App\Models\Order::query()->where('status', 'shipped')->count() ),
            // Stat::make('Order Cancelled', \App\Models\Order::query()->where('status', 'cancelled')->count() )
            Stat::make('Average Price', \Illuminate\Support\Number::currency(\App\Models\Order::query()->avg('grand_total'), 'IDR'), 'IDR' )

        ];
    }
}
