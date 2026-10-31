<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Shared, polymorphic categories.
     *
     * `type` says which list a category belongs to (heritage topic, audio
     * category, news type ...). An item can carry categories of several types
     * at once, e.g. an audio item tagged "المحاضرات" (audio) AND "الخطب"
     * (heritage_topic). That is what powers the
     * "تراث السيدة الزهراء" page and the unified search.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('type', 125)->index(); // App\Enums\CategoryType
            $table->string('name');
            $table->string('slug', 125);
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'slug']);
        });

        Schema::create('categorizables', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->morphs('categorizable');
            $table->timestamps();

            $table->unique(['category_id', 'categorizable_id', 'categorizable_type'], 'categorizables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorizables');
        Schema::dropIfExists('categories');
    }
};
