<?php

namespace Tests\Feature;

use App\Livewire\Contact;
use App\Models\ContactMessage;
use App\Settings\ContactSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, string> */
    protected function validInput(array $overrides = []): array
    {
        return $overrides + [
            'name' => 'زائر كريم',
            'email' => 'visitor@example.com',
            'phone' => '+964 770 000 0000',
            'subject' => 'استفسار عن الإصدارات',
            'message' => 'أود الاستفسار عن إصدارات المؤسسة الجديدة.',
        ];
    }

    protected function fillAndSubmit($component, array $input)
    {
        foreach ($input as $field => $value) {
            $component->set($field, $value);
        }

        return $component->call('submit');
    }

    public function test_a_valid_message_is_stored_and_the_success_state_is_shown(): void
    {
        $component = $this->fillAndSubmit(Livewire::test(Contact::class), $this->validInput());

        $component->assertHasNoErrors()->assertSet('sent', true)->assertSee('تم استلام رسالتك');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'visitor@example.com',
            'subject' => 'استفسار عن الإصدارات',
            'status' => 'new',
        ]);
    }

    public function test_required_fields_fail_with_arabic_messages(): void
    {
        Livewire::test(Contact::class)
            ->call('submit')
            ->assertHasErrors(['name', 'email', 'subject', 'message'])
            ->assertSee('حقل الاسم مطلوب.')
            ->assertSee('حقل البريد الإلكتروني مطلوب.');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_the_email_phone_and_message_length_are_validated(): void
    {
        $component = $this->fillAndSubmit(Livewire::test(Contact::class), $this->validInput([
            'email' => 'not-an-email',
            'phone' => 'call-me-maybe',
            'message' => 'قصيرة',
        ]));

        $component->assertHasErrors(['email', 'phone', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_the_phone_number_is_optional(): void
    {
        $this->fillAndSubmit(Livewire::test(Contact::class), $this->validInput(['phone' => '']))->assertHasNoErrors();

        $this->assertNull(ContactMessage::firstOrFail()->phone);
    }

    public function test_a_filled_honeypot_looks_successful_but_stores_nothing(): void
    {
        $component = $this->fillAndSubmit(Livewire::test(Contact::class), $this->validInput(['website' => 'http://spam.example']));

        $component->assertSet('sent', true);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_sending_too_many_messages_is_throttled(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->fillAndSubmit(Livewire::test(Contact::class), $this->validInput())->assertSet('sent', true);
        }

        $this->fillAndSubmit(Livewire::test(Contact::class), $this->validInput())
            ->assertSet('sent', false)
            ->assertHasErrors('form')
            ->assertSee('لقد أرسلت رسائل كثيرة');

        $this->assertDatabaseCount('contact_messages', 3);
    }

    public function test_the_page_shows_only_the_contact_details_that_are_set(): void
    {
        $this->get(route('contact'))
            ->assertSee('العراق، النجف الأشرف')
            ->assertDontSee('href="tel:', false)
            ->assertDontSee('<iframe', false);

        $settings = app(ContactSettings::class);
        $settings->phone = '+964 770 123 4567';
        $settings->email = 'info@example.com';
        $settings->facebook_url = 'https://facebook.com/example';
        $settings->map_embed_url = 'https://www.google.com/maps/embed?pb=abc';
        $settings->save();

        $this->get(route('contact'))
            ->assertSee('href="tel:+9647701234567"', false)
            ->assertSee('href="mailto:info@example.com"', false)
            ->assertSee('https://facebook.com/example', false)
            ->assertSee('<iframe src="https://www.google.com/maps/embed?pb=abc"', false);
    }

    public function test_a_map_url_that_is_not_https_is_not_embedded(): void
    {
        $settings = app(ContactSettings::class);
        $settings->map_embed_url = 'javascript:alert(1)';
        $settings->save();

        $this->get(route('contact'))->assertDontSee('<iframe', false);
    }
}
