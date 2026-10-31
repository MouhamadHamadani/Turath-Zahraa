<?php

namespace App\Filament\Support;

use App\Enums\CategoryType;
use App\Enums\ContentStatus;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

/**
 * Form and table pieces shared by the content resources, so each resource
 * only declares what is specific to it.
 */
class ResourceParts
{
    /**
     * Title input that fills the slug on create, plus the slug input.
     *
     * @return array<int, TextInput>
     */
    public static function titleAndSlug(string $titleField = 'title'): array
    {
        return [
            TextInput::make($titleField)
                ->required()
                ->maxLength(190)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create'
                    ? $set('slug', Str::slug((string) $state, language: null))
                    : null),
            TextInput::make('slug')
                ->required()
                ->maxLength(190)
                ->unique(ignoreRecord: true),
        ];
    }

    /** Single image stored in a Media Library collection. */
    public static function imageUpload(string $collection): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make($collection)
            ->collection($collection)
            ->image()
            ->imageEditor()
            ->maxSize(8192)
            ->openable();
    }

    /** Single PDF stored in a Media Library collection. */
    public static function pdfUpload(string $collection = 'pdf'): SpatieMediaLibraryFileUpload
    {
        return SpatieMediaLibraryFileUpload::make($collection)
            ->collection($collection)
            ->acceptedFileTypes(['application/pdf'])
            ->maxSize(102400)
            ->openable()
            ->downloadable();
    }

    /** Status, publish date and the featured flag. */
    public static function publishing(bool $featured = true): Section
    {
        return Section::make('النشر')
            ->columns(3)
            ->schema([
                Select::make('status')
                    ->options(ContentStatus::class)
                    ->default(ContentStatus::Draft)
                    ->required()
                    ->native(false),
                DateTimePicker::make('published_at')
                    ->helperText('اتركه فارغًا للنشر فور اعتماد الحالة "منشور". أي تاريخ مستقبلي يؤجّل الظهور.'),
                Toggle::make('is_featured')->visible($featured),
            ]);
    }

    /**
     * One multi-select per category list. Each only touches the categories
     * of its own type, so several lists can share the same `categories` relation.
     */
    public static function categories(CategoryType ...$types): Section
    {
        return Section::make('التصنيفات')
            ->columns(count($types))
            ->schema(array_map(fn (CategoryType $type) => self::categorySelect($type), $types));
    }

    protected static function categorySelect(CategoryType $type): Select
    {
        return Select::make("categories_{$type->value}")
            ->label('تصنيفات: '.$type->getLabel())
            ->multiple()
            ->searchable()
            ->preload()
            ->options(fn () => Category::ofType($type)->pluck('name', 'id')->all())
            ->dehydrated(false)
            ->afterStateHydrated(fn (Select $component, ?Model $record) => $component->state(
                $record?->categoriesOfType($type)->pluck('categories.id')->all() ?? [],
            ))
            ->saveRelationshipsUsing(fn (Model $record, ?array $state) => $record->syncCategoriesOfType($type, $state ?? []));
    }

    public static function statusColumn(): TextColumn
    {
        return TextColumn::make('status')->badge()->sortable();
    }

    public static function publishedAtColumn(): TextColumn
    {
        return TextColumn::make('published_at')->dateTime('Y-m-d H:i')->sortable()->toggleable();
    }

    /**
     * @param  array<int, mixed>  $extra
     * @return array<int, mixed>
     */
    public static function filters(array $extra = [], bool $status = true, bool $categories = true, bool $softDeletes = true): array
    {
        return array_values(array_filter([
            $status ? SelectFilter::make('status')->options(ContentStatus::class) : null,
            $categories ? SelectFilter::make('category')
                ->label('التصنيف')
                ->relationship('categories', 'name')
                ->getOptionLabelFromRecordUsing(fn (Category $category) => "{$category->type->getLabel()} — {$category->name}")
                ->searchable()
                ->preload() : null,
            ...$extra,
            $softDeletes ? TrashedFilter::make() : null,
        ]));
    }

    /** @return array<int, mixed> */
    public static function recordActions(): array
    {
        return [EditAction::make()];
    }

    /** @return array<int, BulkActionGroup> */
    public static function toolbarActions(bool $softDeletes = true): array
    {
        return [
            BulkActionGroup::make($softDeletes
                ? [DeleteBulkAction::make(), ForceDeleteBulkAction::make(), RestoreBulkAction::make()]
                : [DeleteBulkAction::make()]),
        ];
    }

    /** Trashed records must stay reachable so they can be restored. */
    public static function withTrashed(Builder $query): Builder
    {
        return $query->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
