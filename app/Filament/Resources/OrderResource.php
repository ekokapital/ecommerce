<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer Name')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('payment_method')
                            ->options(([
                                'stripe' => 'Stripe',
                                'cod' => 'COD',
                            ]))
                            ->required(),
                        Select::make('payment_status')
                            ->options(([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ]))->default('pending')
                            ->required(),
                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->options(([
                                'new' => 'Pending',
                                'processing' => 'Paid',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'canceled' => 'Canceled',
                            ]))
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'canceled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'canceled' => 'heroicon-m-x-circle',
                            ]),
                        Select::make('currency')
                            ->options([
                                'idr' => 'IDR',
                                'usd' => 'USD'
                            ])->default('idr'),
                        Select::make('shipping_method')
                            ->options([
                                'jne' => 'JNE',
                                'jnt' => 'JNT',
                                'pos' => 'POS Indonesia',
                                'sicepat' => 'SiCepat',
                                'tiki' => 'TIKI',
                            ])->default(''),
                        Textarea::make('notes')
                            ->columnSpanFull()
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->columnSpan(4)
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set)=> $set('unit_amount', \App\Models\Product::find($state)?->price ?? 0))
                                    ->afterStateUpdated(fn($state, Set $set)=> $set('total_amount', \App\Models\Product::find($state)?->price ?? 0)),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Set $set, Get $get)=> $set('total_amount', $state * $get('unit_amount'))),
                                TextInput::make('unit_amount')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(3),
                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(3),
                            ])->columns(12),
                            Placeholder::make('grand_total')
                                ->label('Grand Total')
                                ->content(function(Get $get, Set $set){
                                    $total =0;
                                    if(!$repeaters = $get('items')){
                                        return $total;
                                    }
                                    foreach($repeaters as $key => $repeater){
                                        $total += $get("items.{$key}.total_amount");
                                    }
                                    $set('grand_total', $total);
                                    return Number::currency($total, 'IDR');
                                }),
                                Hidden::make('grand_total')
                                    ->default(0)

                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('status'),
                TextColumn::make('grand_total'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
