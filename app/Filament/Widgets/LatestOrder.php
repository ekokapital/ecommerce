<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrder extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(2)
            ->defaultSort('created_at', 'desc')
            
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('OrderID'),
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('grand_total')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge()->sortable()
                    ->color(fn(string $state): string=>match($state){
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        })
                    ->icon(fn(string $state): string=>match($state){
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-cross',
                        }),
                Tables\Columns\TextColumn::make('payment_method')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->searchable()->sortable()
                ->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Order Date')->sortable()->dateTime(),
            ]);
    }
}

// https://www.youtube.com/watch?v=cBjAAlt5yog&ab_channel=DCodeMania