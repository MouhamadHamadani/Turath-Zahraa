<?php

namespace App\Filament\Resources\Videos;

use App\Enums\CategoryType;
use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\Pages\EditVideo;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Filament\Support\ResourceParts;
use App\Models\Video;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|UnitEnum|null $navigationGroup = 'الأرشيف';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'فيديو';

    protected static ?string $pluralModelLabel = 'الفيديوهات';

    protected static ?string $recordTitleAttribute = 'title';

    /** Accepts a bare 11-character id, or a youtube.com / youtu.be link. */
    public static function extractYoutubeId(?string $value): ?string
    {
        $value = trim((string) $value);

        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', $value, $matches)) {
            return $matches[1];
        }

        return $value === '' ? null : $value;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                TextInput::make('youtube_id')
                    ->required()
                    ->regex('/^[A-Za-z0-9_-]{11}$/')
                    ->validationMessages(['regex' => 'معرّف يوتيوب مكوّن من 11 حرفًا، أو الصق رابط الفيديو وسيُستخرج المعرّف تلقائيًا.'])
                    ->helperText('الصق رابط الفيديو أو المعرّف المكوّن من 11 حرفًا.')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('youtube_id', self::extractYoutubeId($state))),
                ImageEntry::make('youtube_preview')
                    ->label('معاينة')
                    ->state(fn (Get $get) => filled($id = $get('youtube_id')) && preg_match('/^[A-Za-z0-9_-]{11}$/', $id)
                        ? "https://i.ytimg.com/vi/{$id}/hqdefault.jpg"
                        : null)
                    ->imageHeight(120)
                    ->hidden(fn (Get $get) => blank($get('youtube_id'))),
                Select::make('speaker_id')->relationship('speaker', 'name')->searchable()->preload(),
                Select::make('event_id')->relationship('event', 'title')->searchable()->preload(),
                Textarea::make('description')->rows(4)->columnSpanFull(),
                DatePicker::make('recorded_at'),
                TextInput::make('duration_seconds')->numeric()->minValue(1),
                ResourceParts::imageUpload('thumbnail')->helperText('اختياري. عند تركه فارغًا تُستخدم صورة يوتيوب المصغّرة.'),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Video),
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
                TextColumn::make('youtube_id')->copyable()->toggleable(),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
                TextColumn::make('views_count')->numeric()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(ResourceParts::filters())
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
