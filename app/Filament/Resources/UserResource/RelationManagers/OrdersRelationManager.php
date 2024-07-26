<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //    
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('OrderID'),
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


            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('View Order')->url( fn(\App\Models\Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')->icon('heroicon-o-eye'),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
