<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Media Library collections: books/publications -> "cover", "pdf";
     * research_papers -> "pdf".
     * Multiple co-authors can be added later with a pivot; one main author
     * covers the foundation's current needs.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->unsignedSmallInteger('pages_count')->nullable();
            $table->unsignedSmallInteger('published_year')->nullable()->index();
            $table->string('isbn', 20)->nullable();
            $table->string('language', 5)->default('ar');
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('allow_online_reading')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('type')->index(); // App\Enums\PublicationType: magazine, booklet, newsletter, special
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->string('issue_number')->nullable();
            $table->unsignedSmallInteger('pages_count')->nullable();
            $table->unsignedSmallInteger('published_year')->nullable()->index();
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('allow_online_reading')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('research_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('researcher_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('abstract')->nullable();
            $table->unsignedSmallInteger('pages_count')->nullable();
            $table->unsignedSmallInteger('published_year')->nullable()->index();
            $table->boolean('is_downloadable')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_papers');
        Schema::dropIfExists('publications');
        Schema::dropIfExists('books');
    }
};
