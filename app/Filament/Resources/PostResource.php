<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Create a Post')
                ->description('Create/Edit a new post over here')
                ->collapsible()
                ->schema([
                    // ...
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->rules(['min:2','max:5'])->required(),
                    ColorPicker::make('color')->required(),
                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name'),
                        
                        //->options(Category::all()->pluck('name', 'id')),
                    MarkdownEditor::make('content')->required()->columnSpan('full'),
                ])->columns(2),
                
                FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                Group::make()
                ->schema([
                    // ...
                    TagsInput::make('tags')->required(),
                    Checkbox::make('published'),
                ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id'),
                ImageColumn::make('thumbnail'),
                TextColumn::make('title')->toggleable()->sortable()->searchable(),
                ColorColumn::make('color'),
                TextColumn::make('slug'),
                TextColumn::make('category.name'),
                TextColumn::make('tags'),
                CheckboxColumn::make('published'),
                TextColumn::make('created_at')->date('Y M'),
                TextColumn::make('updated_at'),
            ])
            ->filters([
                //
                
                //TernaryFilter::make('published'),
                // SelectFilter::make('category_id')->label('Category')
                //     ->relationship('category', 'name')
                //     ->preload()
                //     //->options(Category::all()->pluck('name', 'id'))
                //     //->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
