<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One table for every author / speaker / researcher.
     * Books, papers, audio, video and articles all point here, so the
     * "الخطباء والباحثون" directory is just a filtered view of this table.
     * The photo is stored through Spatie Media Library (collection: "photo").
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('honorific')->nullable();      // الشيخ، السيد، الدكتور ...
            $table->string('specialty')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_listed')->default(true)->index(); // show in the public directory
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
