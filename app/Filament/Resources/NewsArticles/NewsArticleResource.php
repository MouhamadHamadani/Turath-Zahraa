<?php

namespace App\Filament\Resources\NewsArticles;

use App\Enums\CategoryType;
use App\Filament\Resources\NewsArticles\Pages\CreateNewsArticle;
use App\Filament\Resources\NewsArticles\Pages\EditNewsArticle;
use App\Filament\Resources\NewsArticles\Pages\ListNewsArticles;
use App\Filament\Support\ResourceParts;
use App\Models\NewsArticle;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class NewsArticleResource extends Resource
{
    protected static ?string $model = NewsArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'الأخبار والفعاليات';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'خبر';

    protected static ?string $pluralModelLabel = 'الأخبار';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('الخبر')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                Select::make('event_id')->relationship('event', 'title')->searchable()->preload(),
                ResourceParts::imageUpload('cover'),
                Textarea::make('excerpt')->rows(3)->columnSpanFull(),
                RichEditor::make('body')->required()->columnSpanFull(),
            ]),
            ResourceParts::categories(CategoryType::News),
            ResourceParts::publishing(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')->collection('cover')->height(56),
                TextColumn::make('title')->searchable()->sortable()->limit(60),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
                IconColumn::make('is_featured')->boolean()->toggleable(),
            ])
            ->filters(ResourceParts::filters())
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsArticles::route('/'),
            'create' => CreateNewsArticle::route('/create'),
            'edit' => EditNewsArticle::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
