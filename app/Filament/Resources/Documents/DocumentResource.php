<?php

namespace App\Filament\Resources\Documents;

use App\Enums\CategoryType;
use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\Pages\ListDocuments;
use App\Filament\Support\ResourceParts;
use App\Models\Document;
use BackedEnum;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'الأرشيف';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'وثيقة';

    protected static ?string $pluralModelLabel = 'الوثائق';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                Textarea::make('description')->rows(4)->columnSpanFull(),
                TextInput::make('source')->maxLength(190)->helperText('المكتبة أو الأرشيف المالك للأصل.'),
                TextInput::make('date_label')->maxLength(190)->helperText('تاريخ نصّي، مثل: القرن الثالث عشر الهجري.'),
                TextInput::make('year')->numeric()->minValue(1000)->maxValue(2100),
                TextInput::make('pages_count')->numeric()->minValue(1),
                Toggle::make('is_rare'),
                Toggle::make('is_downloadable'),
            ]),
            Section::make('صفحات الوثيقة')->schema([
                SpatieMediaLibraryFileUpload::make('pages')
                    ->collection('pages')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->maxSize(51200)
                    ->openable()
                    ->helperText('صفحة واحدة لكل ملف. يمكن إعادة ترتيبها بالسحب.'),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Document),
            ResourceParts::publishing(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(60),
                TextColumn::make('source')->toggleable(),
                TextColumn::make('year')->sortable()->toggleable(),
                IconColumn::make('is_rare')->boolean(),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
            ])
            ->filters(ResourceParts::filters([TernaryFilter::make('is_rare')]))
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
