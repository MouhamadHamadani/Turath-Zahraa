<?php

namespace App\Filament\Resources\Projects;

use App\Enums\ProjectStatus;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Support\ResourceParts;
use App\Models\Project;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'مشروع';

    protected static ?string $pluralModelLabel = 'المشاريع والبرامج';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('المشروع')->columns(2)->schema([
                ...ResourceParts::titleAndSlug('name'),
                Select::make('status')->options(ProjectStatus::class)->default(ProjectStatus::Ongoing)->required()->native(false),
                ResourceParts::imageUpload('cover'),
                Textarea::make('goal')->rows(3)->columnSpanFull(),
                RichEditor::make('description')->columnSpanFull(),
                TextInput::make('beneficiaries')->maxLength(190),
                Textarea::make('participation_info')->rows(3),
                DatePicker::make('started_at'),
                DatePicker::make('ended_at')->after('started_at'),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
                Toggle::make('is_featured'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->limit(60),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('started_at')->date('Y-m-d')->sortable()->toggleable(),
                IconColumn::make('is_featured')->boolean()->toggleable(),
            ])
            ->filters(ResourceParts::filters([SelectFilter::make('project_status')->label('الحالة')->attribute('status')->options(ProjectStatus::class)], status: false, categories: false))
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return ResourceParts::withTrashed(parent::getRecordRouteBindingEloquentQuery());
    }
}
