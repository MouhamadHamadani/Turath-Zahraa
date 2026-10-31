<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 4 (pending foundation decisions: free vs paid membership,
     * payment gateway). Safe to leave unmigrated until confirmed.
     */
    public function up(): void
    {
        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0); // 0 = free
            $table->char('currency', 3)->default('USD');
            $table->json('benefits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete(); // Fortify users
            $table->foreignId('membership_tier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('status')->default('pending')->index(); // pending, active, suspended
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('USD');
            $table->string('method')->nullable();                 // bank_transfer, card, cash
            $table->string('status')->default('pending')->index(); // App\Enums\DonationStatus
            $table->string('gateway')->nullable();
            $table->string('gateway_reference')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->timestamp('donated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('members');
        Schema::dropIfExists('membership_tiers');
    }
};
