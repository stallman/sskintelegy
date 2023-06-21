<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('list')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('balance'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('type'),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('balance'),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->form([Forms\Components\TextInput::make('name')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['name'],
                            fn(Builder $query, $value): Builder => $query->where('name', 'like', '%'.$value.'%'),
                        )),

                Tables\Filters\Filter::make('email')
                    ->form([Forms\Components\TextInput::make('email')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['email'],
                            fn(Builder $query, $value): Builder => $query->where('email', 'like', '%'.$value.'%'),
                        )),
                Tables\Filters\SelectFilter::make('type')
                    ->multiple()
                    ->options([
                        'test' => 'Test',
                        'valid' => 'Valid',
                        'new' => 'New',
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
                Tables\Filters\Filter::make('balance')
                    ->form([Forms\Components\TextInput::make('balance')])
                    ->query(fn(Builder $query, array $data): Builder => $query
                        ->when(
                            $data['balance'],
                            fn(Builder $query, $value): Builder => $query->where('balance', '=', $value),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
