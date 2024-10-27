<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email()
                        ->unique(ignorable: fn($record) => $record),

                    DateTimePicker::make('email_verified_at')
                        ->label('Email Verified At')
                        ->nullable(),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn(string $context) => $context === 'create')
                        ->minLength(8)
                        ->dehydrateStateUsing(fn($state) => bcrypt($state)),

                    Toggle::make('admin')
                        ->label('Admin')
                        ->default(false),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email_verified_at')
                    ->label('Email Verified At')
                    ->date()
                    ->sortable()
                    ->default(null),

                IconColumn::make('admin')
                    ->label('Admin')
                    ->sortable()
                    ->boolean()
                    ->default(false),

                TextColumn::make('password')
                    ->label('Password')
                    ->hidden() // Optional: Hides password for security
                    ->default(''),

                TextColumn::make('remember_token')
                    ->label('Remember Token')
                    ->hidden()
                    ->default(''),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->date()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
