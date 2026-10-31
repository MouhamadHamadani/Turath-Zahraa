<?php

namespace App\Filament\Pages;

use App\Settings\ContactSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageContactSettings extends SettingsPage
{
    protected static string $settings = ContactSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|UnitEnum|null $navigationGroup = 'الإعدادات';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'بيانات التواصل';

    protected static ?string $title = 'بيانات التواصل';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('التواصل')
                ->description('أي حقل يُترك فارغًا لن يظهر في الموقع.')
                ->columns(2)
                ->schema([
                    TextInput::make('address')->label('العنوان')->maxLength(190)->columnSpanFull(),
                    TextInput::make('phone')->label('الهاتف')->tel()->maxLength(40)->extraInputAttributes(['dir' => 'ltr']),
                    TextInput::make('whatsapp')->label('واتساب')->tel()->maxLength(40)->extraInputAttributes(['dir' => 'ltr'])
                        ->helperText('بالصيغة الدولية، مثل 9647XXXXXXXXX.'),
                    TextInput::make('email')->label('البريد الإلكتروني')->email()->maxLength(190)->extraInputAttributes(['dir' => 'ltr']),
                    TextInput::make('map_embed_url')->label('رابط تضمين الخريطة')->url()->maxLength(500)
                        ->helperText('رابط src من خيار "تضمين خريطة" في خرائط Google. يظهر في صفحة التواصل فقط عند تعبئته.'),
                ]),
            Section::make('مواقع التواصل الاجتماعي')->columns(2)->schema([
                TextInput::make('facebook_url')->label('فيسبوك')->url()->maxLength(190),
                TextInput::make('instagram_url')->label('إنستغرام')->url()->maxLength(190),
                TextInput::make('youtube_url')->label('يوتيوب')->url()->maxLength(190),
                TextInput::make('telegram_url')->label('تيليغرام')->url()->maxLength(190),
                TextInput::make('x_url')->label('إكس (تويتر)')->url()->maxLength(190),
            ]),
        ]);
    }
}
