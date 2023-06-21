<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(
                        fn(Order $record): string => ([
                                'deposit' => '+',
                                'purchase' => '-',
                                null => '',
                            ][$record->transaction_type]).$record->amount
                    ),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('transaction_type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('user_id')
                    ->form([Forms\Components\TextInput::make('user_id')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['user_id'],
                            fn(Builder $query, $value): Builder => $query->where('user_id', '=', $value),
                        )),
                Tables\Filters\Filter::make('amount')
                    ->form([Forms\Components\TextInput::make('amount')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['amount'],
                            fn(Builder $query, $value): Builder => $query->where('amount', '=', $value),
                        )),
                Tables\Filters\Filter::make('currency')
                    ->form([Forms\Components\TextInput::make('currency')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['currency'],
                            fn(Builder $query, $value): Builder => $query->where('currency', 'like', '%'.$value.'%'),
                        )),
                Tables\Filters\Filter::make('status'),
                Tables\Filters\SelectFilter::make('transaction_type')
                    ->multiple()
                    ->options([
                        'deposit' => 'Deposit',
                        'purchase' => 'Skin purchase',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([DatePicker::make('created_at')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['created_at'],
                            fn(Builder $query, $value): Builder => $query->whereDate('created_at', '=', $value),
                        )),
                Tables\Filters\Filter::make('updated_at')
                    ->form([DatePicker::make('updated_at')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['updated_at'],
                            fn(Builder $query, $value): Builder => $query->whereDate('updated_at', '=', $value),
                        )),
            ], layout: Tables\Filters\Layout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()->icon(null)->label(''),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
