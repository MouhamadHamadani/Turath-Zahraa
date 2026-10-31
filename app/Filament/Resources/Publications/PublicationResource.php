<?php

namespace App\Filament\Resources\Publications;

use App\Enums\CategoryType;
use App\Enums\PublicationType;
use App\Filament\Resources\Publications\Pages\CreatePublication;
use App\Filament\Resources\Publications\Pages\EditPublication;
use App\Filament\Resources\Publications\Pages\ListPublications;
use App\Filament\Support\ResourceParts;
use App\Models\Publication;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static string|UnitEnum|null $navigationGroup = 'المكتبة';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'مطبوع';

    protected static ?string $pluralModelLabel = 'المطبوعات والإصدارات';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                Select::make('type')->options(PublicationType::class)->required()->native(false),
                ...ResourceParts::titleAndSlug(),
                Select::make('author_id')->relationship('author', 'name')->searchable()->preload(),
                TextInput::make('issue_number')->maxLength(190),
                Textarea::make('summary')->rows(4)->columnSpanFull(),
                TextInput::make('pages_count')->numeric()->minValue(1),
                TextInput::make('published_year')->numeric()->minValue(1000)->maxValue(2100),
            ]),
            Section::make('الملفات')->columns(2)->schema([
                ResourceParts::imageUpload('cover'),
                ResourceParts::pdfUpload(),
                Toggle::make('is_downloadable'),
                Toggle::make('allow_online_reading'),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Publication),
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
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('issue_number')->toggleable(),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
            ])
            ->filters(ResourceParts::filters([SelectFilter::make('type')->options(PublicationType::class)]))
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublications::route('/'),
            'create' => CreatePublication::route('/create'),
            'edit' => EditPublication::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
