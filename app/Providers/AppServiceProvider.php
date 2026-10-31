<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->bootFilamentLabels();
    }

    /**
     * Label every Filament field, column and filter from lang/*\/fields.php
     * by attribute name, unless the component sets its own label.
     */
    protected function bootFilamentLabels(): void
    {
        $label = fn (string $name): ?string => Lang::has("fields.{$name}") ? __("fields.{$name}") : null;

        Field::configureUsing(fn (Field $field) => $field->label(fn () => $label($field->getName())));
        Column::configureUsing(fn (Column $column) => $column->label(fn () => $label($column->getName())));
        BaseFilter::configureUsing(fn (BaseFilter $filter) => $filter->label(fn () => $label($filter->getName())));
    }
}
