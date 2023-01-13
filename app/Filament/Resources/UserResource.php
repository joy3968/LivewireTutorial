<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('이름')->required(),
                Forms\Components\TextInput::make('email')->label('이메일')->email()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('이름')->sortable()->action(
                    Action::make('select')
                        ->requiresConfirmation()
                        ->action(function (Post $record): void {
                            $this->dispatchBrowserEvent('select-post', [
                                'post' => $record->getKey(),
                            ]);
                        }),
                )
                ,
                Tables\Columns\TextColumn::make('email')->label('이메일')->sortable()->searchable()->tooltip('사용자 이메일입니다.'),
                Tables\Columns\TextColumn::make('created_at')->label('생성일')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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

    // 기본 정렬
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'email';
    }

    // 기본 정렬 설정 (오름차순/내림차순)
    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }

    // 세션 내 검색 유지
    protected function shouldPersistTableSearchInSession(): bool
    {
        return true;
    }

    protected function shouldPersistTableColumnSearchInSession(): bool
    {
        return true;
    }
}
