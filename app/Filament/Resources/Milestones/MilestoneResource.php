<?php

namespace App\Filament\Resources\Milestones;

use App\Filament\Resources\Milestones\Pages\CreateMilestone;
use App\Filament\Resources\Milestones\Pages\EditMilestone;
use App\Filament\Resources\Milestones\Pages\ListMilestones;
use App\Filament\Support\ResourceParts;
use App\Models\Milestone;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'محطة';

    protected static ?string $pluralModelLabel = 'مسيرة المؤسسة';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('year')->numeric()->required()->minValue(1900)->maxValue(2100),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
                TextInput::make('title')->required()->maxLength(190)->columnSpanFull(),
                Textarea::make('description')->rows(4)->columnSpanFull(),
                ResourceParts::imageUpload('image')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->collection('image')->height(48),
                TextColumn::make('year')->sortable(),
                TextColumn::make('title')->searchable()->limit(60),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions(softDeletes: false));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMilestones::route('/'),
            'create' => CreateMilestone::route('/create'),
            'edit' => EditMilestone::route('/{record}/edit'),
        ];
    }
}
