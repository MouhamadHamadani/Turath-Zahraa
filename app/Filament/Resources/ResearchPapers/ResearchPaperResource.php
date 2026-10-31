<?php

namespace App\Filament\Resources\ResearchPapers;

use App\Enums\CategoryType;
use App\Filament\Resources\ResearchPapers\Pages\CreateResearchPaper;
use App\Filament\Resources\ResearchPapers\Pages\EditResearchPaper;
use App\Filament\Resources\ResearchPapers\Pages\ListResearchPapers;
use App\Filament\Support\ResourceParts;
use App\Models\ResearchPaper;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ResearchPaperResource extends Resource
{
    protected static ?string $model = ResearchPaper::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'المكتبة';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'بحث';

    protected static ?string $pluralModelLabel = 'البحوث';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                Select::make('researcher_id')->relationship('researcher', 'name')->searchable()->preload(),
                TextInput::make('published_year')->numeric()->minValue(1000)->maxValue(2100),
                Textarea::make('abstract')->rows(5)->columnSpanFull(),
                TextInput::make('pages_count')->numeric()->minValue(1),
            ]),
            Section::make('الملفات')->columns(2)->schema([
                ResourceParts::pdfUpload(),
                Toggle::make('is_downloadable')->default(true),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Research),
            ResourceParts::publishing(featured: false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(70),
                TextColumn::make('researcher.name')->label('الباحث')->searchable()->toggleable(),
                TextColumn::make('published_year')->sortable()->toggleable(),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
            ])
            ->filters(ResourceParts::filters())
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchPapers::route('/'),
            'create' => CreateResearchPaper::route('/create'),
            'edit' => EditResearchPaper::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
