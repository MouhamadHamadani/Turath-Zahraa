<?php

namespace App\Filament\Pages;

use App\Settings\HomeStatsSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageHomeStatsSettings extends SettingsPage
{
    protected static string $settings = HomeStatsSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'الإعدادات';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'أرقام الصفحة الرئيسية';

    protected static ?string $title = 'أرقام الصفحة الرئيسية';

    public function form(Schema $schema): Schema
    {
        $helper = 'اتركه فارغًا ليُحسب العدد من المحتوى المنشور تلقائيًا.';

        return $schema->components([
            Section::make('تعديل يدوي للأرقام')->description($helper)->columns(2)->schema([
                TextInput::make('books_count')->label('الكتب والإصدارات')->numeric()->minValue(0),
                TextInput::make('audio_count')->label('الصوتيات')->numeric()->minValue(0),
                TextInput::make('videos_count')->label('الفيديوهات')->numeric()->minValue(0),
                TextInput::make('archive_count')->label('الصور والوثائق')->numeric()->minValue(0),
                TextInput::make('events_count')->label('البرامج والفعاليات')->numeric()->minValue(0),
                TextInput::make('followers_count')->label('المتابعون على مواقع التواصل')->numeric()->minValue(0)
                    ->helperText('لا يمكن حسابه من الموقع، لذا يُدخل يدويًا. اتركه فارغًا لإخفاء العبارة.'),
            ]),
        ]);
    }
}
