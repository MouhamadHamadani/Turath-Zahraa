<?php

namespace App\Filament\Resources\People;

use App\Filament\Resources\People\Pages\CreatePerson;
use App\Filament\Resources\People\Pages\EditPerson;
use App\Filament\Resources\People\Pages\ListPeople;
use App\Filament\Support\ResourceParts;
use App\Models\Person;
use BackedEnum;
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

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'شخصية';

    protected static ?string $pluralModelLabel = 'الخطباء والباحثون';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->columns(2)->schema([
                TextInput::make('honorific')->maxLength(190)->placeholder('الشيخ، السيد، الدكتور ...'),
                ...ResourceParts::titleAndSlug('name'),
                TextInput::make('specialty')->maxLength(190),
                ResourceParts::imageUpload('photo'),
                Textarea::make('bio')->rows(5)->columnSpanFull(),
            ]),
            Section::make('العرض')->columns(3)->schema([
                Toggle::make('is_listed')->default(true),
                Toggle::make('is_featured'),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')->collection('photo')->circular(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('honorific')->toggleable(),
                TextColumn::make('specialty')->searchable()->toggleable(),
                IconColumn::make('is_listed')->boolean(),
                IconColumn::make('is_featured')->boolean()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime('Y-m-d')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(ResourceParts::filters(status: false, categories: false))
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
