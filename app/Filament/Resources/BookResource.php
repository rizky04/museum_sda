<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Sejarah Majapahit'),

                Forms\Components\TextInput::make('author')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('cover_image')
                    ->image() // Validasi hanya gambar
                    ->directory('covers') // Disimpan di storage/app/public/covers
                    ->required(),

                Forms\Components\FileUpload::make('pdf_file')
                    ->acceptedFileTypes(['application/pdf']) // Hanya boleh PDF
                    ->directory('documents')
                    ->preserveFilenames()
                    ->required(),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\ImageColumn::make('cover_image')
                ->circular(),
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('author')
                ->searchable(),
            Tables\Columns\TextColumn::make('category.category_name')
                ->badge(),
                Tables\Columns\TextColumn::make('view_count')
                ->label('Dilihat')
                ->suffix(' kali')
                ->sortable()
                ->color('success')
                ->icon('heroicon-m-eye'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                ->relationship('category', 'category_name')
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
