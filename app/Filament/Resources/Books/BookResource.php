<?php

namespace App\Filament\Resources\Books;

use App\Enums\CategoryType;
use App\Filament\Resources\Books\Pages\CreateBook;
use App\Filament\Resources\Books\Pages\EditBook;
use App\Filament\Resources\Books\Pages\ListBooks;
use App\Filament\Support\ResourceParts;
use App\Models\Book;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'المكتبة';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'كتاب';

    protected static ?string $pluralModelLabel = 'الكتب';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                Select::make('author_id')->relationship('author', 'name')->searchable()->preload(),
                TextInput::make('language')->default('ar')->maxLength(5)->required(),
                Textarea::make('summary')->rows(4)->columnSpanFull(),
                TextInput::make('pages_count')->numeric()->minValue(1),
                TextInput::make('published_year')->numeric()->minValue(1000)->maxValue(2100),
                TextInput::make('isbn')->maxLength(20),
            ]),
            Section::make('الملفات')->columns(2)->schema([
                ResourceParts::imageUpload('cover'),
                ResourceParts::pdfUpload(),
                Toggle::make('is_downloadable'),
                Toggle::make('allow_online_reading'),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Book),
            ResourceParts::publishing(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')->collection('cover')->height(56),
                TextColumn::make('title')->searchable()->sortable()->limit(60),
                TextColumn::make('author.name')->label('المؤلف')->searchable()->toggleable(),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
                IconColumn::make('is_featured')->boolean()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('views_count')->numeric()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(ResourceParts::filters())
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBooks::route('/'),
            'create' => CreateBook::route('/create'),
            'edit' => EditBook::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
