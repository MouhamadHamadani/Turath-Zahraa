<?php

namespace App\Models\Concerns;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCategories
{
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable')->withTimestamps();
    }

    /** Only the categories of one list, e.g. the heritage topics of this item. */
    public function categoriesOfType(CategoryType $type): MorphToMany
    {
        return $this->categories()->where('categories.type', $type->value);
    }

    /**
     * Replace this item's categories of one type, leaving other types untouched.
     *
     * @param  array<int, int|string>  $ids
     */
    public function syncCategoriesOfType(CategoryType $type, array $ids): void
    {
        $ids = array_map('intval', $ids);
        $current = $this->categoriesOfType($type)->pluck('categories.id')->all();

        $this->categories()->detach(array_diff($current, $ids));
        $this->categories()->syncWithoutDetaching($ids);
    }

    public function scopeInCategory(Builder $query, Category|int $category): Builder
    {
        $id = $category instanceof Category ? $category->getKey() : $category;

        return $query->whereHas('categories', fn (Builder $q) => $q->whereKey($id));
    }
}
