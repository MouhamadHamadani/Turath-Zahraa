<?php

namespace App\Filament\Resources\Categories;

use App\Enums\CategoryType;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Support\ResourceParts;
use App\Models\Category;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'المحتوى';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'تصنيف';

    protected static ?string $pluralModelLabel = 'التصنيفات';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                Select::make('type')
                    ->options(CategoryType::class)
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('parent_id', null)),
                Select::make('parent_id')
                    ->relationship(
                        'parent',
                        'name',
                        fn (Builder $query, Get $get, ?Category $record) => $query
                            ->where('type', $get('type'))
                            ->when($record, fn (Builder $q) => $q->whereKeyNot($record->getKey())),
                    )
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(190)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create'
                        ? $set('slug', Str::slug((string) $state, language: null))
                        : null),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(125)
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('type', $get('type'))),
                Textarea::make('description')->rows(3)->columnSpanFull(),
                TextInput::make('icon')->maxLength(190)->helperText('اسم أيقونة Font Awesome، مثل fa-solid fa-book'),
                TextInput::make('sort_order')->numeric()->default(0)->required(),
                Toggle::make('is_active')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->defaultGroup('type')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('parent.name')->label('التصنيف الأب')->placeholder('—'),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')->options(CategoryType::class),
                TernaryFilter::make('is_active'),
            ])
            ->recordActions(ResourceParts::recordActions())
            ->toolbarActions(ResourceParts::toolbarActions(softDeletes: false));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
