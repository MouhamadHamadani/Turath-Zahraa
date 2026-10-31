<?php

namespace App\Filament\Resources\AudioTracks;

use App\Enums\CategoryType;
use App\Filament\Resources\AudioTracks\Pages\CreateAudioTrack;
use App\Filament\Resources\AudioTracks\Pages\EditAudioTrack;
use App\Filament\Resources\AudioTracks\Pages\ListAudioTracks;
use App\Filament\Support\ResourceParts;
use App\Models\AudioTrack;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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

class AudioTrackResource extends Resource
{
    protected static ?string $model = AudioTrack::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    protected static string|UnitEnum|null $navigationGroup = 'الأرشيف';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'تسجيل صوتي';

    protected static ?string $pluralModelLabel = 'الصوتيات';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                Select::make('speaker_id')->relationship('speaker', 'name')->searchable()->preload(),
                Select::make('event_id')->relationship('event', 'title')->searchable()->preload(),
                Textarea::make('description')->rows(4)->columnSpanFull(),
                DatePicker::make('recorded_at'),
                TextInput::make('duration_seconds')->numeric()->minValue(1),
            ]),
            Section::make('الملفات')->columns(2)->schema([
                SpatieMediaLibraryFileUpload::make('audio')
                    ->collection('audio')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/mp4', 'audio/x-m4a', 'audio/aac', 'audio/wav', 'audio/x-wav', 'audio/ogg'])
                    ->maxSize(512000)
                    ->openable()
                    ->downloadable(),
                ResourceParts::imageUpload('thumbnail'),
                Toggle::make('is_downloadable'),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Audio),
            ResourceParts::publishing(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(60),
                TextColumn::make('speaker.name')->label('الخطيب/المتحدث')->searchable()->toggleable(),
                TextColumn::make('recorded_at')->date('Y-m-d')->sortable()->toggleable(),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
                TextColumn::make('plays_count')->numeric()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(ResourceParts::filters())
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAudioTracks::route('/'),
            'create' => CreateAudioTrack::route('/create'),
            'edit' => EditAudioTrack::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
