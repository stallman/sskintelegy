<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
                Forms\Components\Select::make('category')
                    ->relationship('category', 'name')
                    ->preload(),
                Forms\Components\TextInput::make('price'),
                Forms\Components\TextInput::make('amount'),
//                Forms\Components\TextInput::make('param1')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('param2')
//                    ->maxLength(255),
                Forms\Components\TextInput::make('buys')
                    ->required(),
                Forms\Components\Textarea::make('imageurl')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('param1'),
                Tables\Columns\TextColumn::make('param2'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('buys'),
                Tables\Columns\TextColumn::make('imageurl'),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->form([Forms\Components\TextInput::make('name')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['name'],
                            fn(Builder $query, $value): Builder => $query->where('name', 'like', '%'.$value.'%'),
                        )),
                Tables\Filters\SelectFilter::make('type')
                    ->multiple()
                    ->options([
                        'rifle' => 'Rifle',
                        'knife' => 'Knife',
                        'gloves' => 'Gloves',
                    ]),
                Tables\Filters\Filter::make('price')
                    ->form([Forms\Components\TextInput::make('price')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['price'],
                            fn(Builder $query, $value): Builder => $query->where('price', '=', $value),
                        )),
                Tables\Filters\Filter::make('amount')
                    ->form([Forms\Components\TextInput::make('amount')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['amount'],
                            fn(Builder $query, $value): Builder => $query->where('amount', '=', $value),
                        )),
                Tables\Filters\Filter::make('buys')
                    ->form([Forms\Components\TextInput::make('buys')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['buys'],
                            fn(Builder $query, $value): Builder => $query->where('buys', '=', $value),
                        )),
                Tables\Filters\Filter::make('imageurl')
                    ->form([Forms\Components\TextInput::make('imageurl')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['imageurl'],
                            fn(Builder $query, $value): Builder => $query->where('imageurl', 'like', '%'.$value.'%'),
                        )),
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
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
