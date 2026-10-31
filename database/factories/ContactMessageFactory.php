<?php

namespace Database\Factories;

use App\Models\ContactMessage;

/**
 * @extends ArabicFactory<ContactMessage>
 */
class ContactMessageFactory extends ArabicFactory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => null,
            'subject' => $this->title(3),
            'message' => $this->paragraph(),
            'status' => 'new',
            'ip_address' => $this->faker->ipv4(),
        ];
    }

    public function read(): static
    {
        return $this->state(fn () => ['status' => 'read', 'read_at' => now()]);
    }
}
