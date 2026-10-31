<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use App\Settings\ContactSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Contact extends Component
{
    protected const MAX_ATTEMPTS = 3;

    protected const DECAY_SECONDS = 600;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $subject = '';

    public string $message = '';

    /** Honeypot: hidden from people, filled in by bots. */
    public string $website = '';

    public bool $sent = false;

    /** @return array<string, array<int, string>> */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\-\s]*$/'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function submit(): void
    {
        $key = 'contact:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);
            $this->addError('form', "لقد أرسلت رسائل كثيرة خلال وقت قصير. يرجى المحاولة بعد {$minutes} دقيقة.");

            return;
        }

        RateLimiter::hit($key, self::DECAY_SECONDS);

        if ($this->website !== '') {
            // A bot: look successful, store nothing.
            $this->sent = true;

            return;
        }

        $data = $this->validate();

        ContactMessage::create([
            ...$data,
            'phone' => $data['phone'] ?: null,
            'status' => 'new',
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 250, ''),
        ]);

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->sent = true;
    }

    public function render(): View
    {
        return view('livewire.contact', ['contact' => app(ContactSettings::class)])
            ->title('تواصل معنا')
            ->layoutData(['description' => 'راسل مؤسسة إحياء تراث الصديقة الشهيدة (ع) عبر النموذج أو بيانات التواصل.']);
    }
}
