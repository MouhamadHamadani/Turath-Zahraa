<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Support\ResourceParts;
use App\Models\Event;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'الأخبار والفعاليات';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'فعالية';

    protected static ?string $pluralModelLabel = 'الفعاليات';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                ...ResourceParts::titleAndSlug(),
                Select::make('project_id')->relationship('project', 'name')->searchable()->preload(),
                ResourceParts::imageUpload('cover'),
                RichEditor::make('description')->columnSpanFull(),
            ]),
            Section::make('الزمان والمكان')->columns(2)->schema([
                TextInput::make('venue')->required()->maxLength(190),
                TextInput::make('city')->maxLength(190),
                DateTimePicker::make('starts_at')->required(),
                DateTimePicker::make('ends_at')->after('starts_at'),
                TextInput::make('registration_url')->url()->maxLength(190),
                TextInput::make('live_stream_url')->url()->maxLength(190),
            ]),
            ResourceParts::publishing(featured: false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('starts_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(60),
                TextColumn::make('starts_at')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('venue')->toggleable(),
                TextColumn::make('city')->toggleable(isToggledHiddenByDefault: true),
                ResourceParts::statusColumn(),
                ResourceParts::publishedAtColumn(),
            ])
            ->filters(ResourceParts::filters(categories: false))
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
