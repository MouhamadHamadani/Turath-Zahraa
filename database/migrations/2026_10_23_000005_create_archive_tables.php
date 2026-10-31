<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Media Library collections:
     *  audio_tracks -> "audio" (S3/CDN), "thumbnail"
     *  videos       -> "thumbnail" (optional, YouTube thumb used otherwise)
     *  photos       -> "image" (conversions: thumb + web; original kept hi-res)
     *  documents    -> "scan" (hi-res original), "pages" (multi-page scans)
     * Videos are NOT uploaded: only the YouTube id is stored.
     */
    public function up(): void
    {
        Schema::create('audio_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('recorded_at')->nullable()->index();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('plays_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('youtube_id', 20);
            $table->text('description')->nullable();
            $table->date('recorded_at')->nullable()->index();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->string('credit')->nullable();
            $table->date('taken_at')->nullable();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('source')->nullable();            // المكتبة / الأرشيف المالك للأصل
            $table->string('date_label')->nullable();        // تاريخ نصّي (هجري/تقريبي)
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->unsignedSmallInteger('pages_count')->nullable();
            $table->boolean('is_rare')->default(false);
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('audio_tracks');
    }
};
