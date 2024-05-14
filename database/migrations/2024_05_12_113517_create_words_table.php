<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->integer('chapter_id');
            $table->string('char_type_name');
            $table->string('code_v1');
            $table->integer('line_number');
            $table->string('location');
            $table->integer('offset_right_percent');
            $table->integer('position');
            $table->string('text');
            $table->string('text_uthmani');
            $table->integer('verse_id');
            $table->integer('verse_number');
            $table->integer('width_percent');
            $table->integer('word_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
