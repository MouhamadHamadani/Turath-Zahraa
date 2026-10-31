<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_fortify_login_page_renders_in_arabic(): void
    {
        $this->get('/login')->assertOk()->assertSee('تسجيل الدخول')->assertSee('dir="rtl"', false);
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', ['name' => 'x', 'email' => 'x@example.test', 'password' => 'password-123', 'password_confirmation' => 'password-123'])->assertNotFound();
    }

    public function test_an_admin_who_logs_in_lands_on_the_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect('/admin');

        $this->get('/admin')->assertOk();
    }

    public function test_a_regular_user_can_log_in_but_not_enter_the_panel(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/admin');

        $this->get('/admin')->assertForbidden();
    }

    public function test_wrong_credentials_are_rejected(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_is_admin_cannot_be_mass_assigned(): void
    {
        $user = new User(['name' => 'x', 'email' => 'x@example.test', 'is_admin' => true]);

        $this->assertFalse((bool) $user->is_admin);
    }
}
