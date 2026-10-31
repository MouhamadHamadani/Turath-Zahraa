<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // المقالات والدراسات (short written pieces, unlike research_papers)
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->unsignedSmallInteger('reading_minutes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        // Static text pages: about, vision, mission, goals, privacy, terms, rights ...
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('body')->nullable();
            $table->string('status')->default('published');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // About-page timeline
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Key/value store editable from the admin panel:
        // hero text, address/phone/email/whatsapp, social links, map embed,
        // optional manual overrides for the homepage numbers.
        // Schema::create('settings', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('group')->default('general')->index();
        //     $table->string('key')->unique();
        //     $table->json('value')->nullable();
        //     $table->timestamps();
        // });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('new')->index(); // new, read, replied, spam
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        // Schema::dropIfExists('settings');
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('articles');
    }
};
