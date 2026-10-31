<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGeneralSettings extends SettingsPage
{
    protected static string $settings = GeneralSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'الإعدادات';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'إعدادات الموقع';

    protected static ?string $title = 'إعدادات الموقع';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('الهوية')->columns(2)->schema([
                TextInput::make('site_name')->label('اسم المؤسسة')->required()->maxLength(190),
                TextInput::make('headquarters')->label('مقر المؤسسة')->required()->maxLength(190),
                Textarea::make('tagline')->label('وصف مختصر (التذييل)')->required()->rows(2)->columnSpanFull(),
            ]),
            Section::make('الصفحة الرئيسية')->schema([
                TextInput::make('hero_title')->label('عنوان الواجهة')->required()->maxLength(190),
                Textarea::make('hero_subtitle')->label('نص الواجهة')->required()->rows(3),
                Textarea::make('about_summary')->label('نبذة "من نحن"')->required()->rows(4),
            ]),
            Section::make('أهداف المؤسسة')->schema([
                Repeater::make('goals')
                    ->label('الأهداف')
                    ->schema([
                        TextInput::make('title')->label('العنوان')->required()->maxLength(190),
                        Textarea::make('description')->label('الوصف')->required()->rows(2),
                    ])
                    ->columns(2)
                    ->reorderable()
                    ->itemLabel(fn (array $state) => $state['title'] ?? null)
                    ->collapsible(),
            ]),
        ]);
    }
}
