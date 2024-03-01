<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-m-folder';

    protected static ?string $modelLabel = 'Post Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')->required()
                ->live(onBlur:true)
                ->afterStateUpdated(function(
                    string $operation, 
                    string $state, 
                    Forms\Set $set, 
                    Forms\Get $get,
                    Category $category){
                    //dump($operation);// $operation stand for what are you doing current you are edit (some time in create)
                    //dump($state);// $state stand for where you are editing current name

                    //$set('slug', 'my-slug');
                    //$set('field want bind , value that bind);

                    if($operation === 'edit'){
                        return;
                    }
                    $set('slug', Str::slug($state));

                    //dump($category);
                }),
                TextInput::make('slug')->required(), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('slug'),
                TextColumn::make('created_at')->date('M Y'),
                TextColumn::make('updated_at'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
