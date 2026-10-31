<?php

namespace App\Filament\Resources\Photos;

use App\Enums\CategoryType;
use App\Filament\Resources\Photos\Pages\CreatePhoto;
use App\Filament\Resources\Photos\Pages\EditPhoto;
use App\Filament\Resources\Photos\Pages\ListPhotos;
use App\Filament\Support\ResourceParts;
use App\Models\Photo;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PhotoResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'الأرشيف';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'صورة';

    protected static ?string $pluralModelLabel = 'الصور';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('الصورة')->columns(2)->schema([
                ResourceParts::imageUpload('image')->required()->columnSpanFull()
                    ->helperText('يُحفظ الأصل بدقته الكاملة، وتُنشأ نسخ مصغّرة للعرض.'),
                TextInput::make('title')->maxLength(190),
                Select::make('event_id')->relationship('event', 'title')->searchable()->preload(),
                Textarea::make('caption')->rows(3)->columnSpanFull(),
                TextInput::make('credit')->maxLength(190),
                DatePicker::make('taken_at'),
                TextInput::make('year')->numeric()->minValue(1000)->maxValue(2100),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
            ]),
            ResourceParts::categories(CategoryType::HeritageTopic, CategoryType::Photo),
            ResourceParts::publishing(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('thumb')->height(56),
                TextColumn::make('title')->searchable()->sortable()->limit(50)->placeholder('—'),
                TextColumn::make('year')->sortable()->toggleable(),
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
            'index' => ListPhotos::route('/'),
            'create' => CreatePhoto::route('/create'),
            'edit' => EditPhoto::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
