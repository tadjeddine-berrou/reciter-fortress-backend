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
        Schema::create('mutashabihs', function (Blueprint $table) {
            $table->id();
            $table->integer('chapter_id');
            $table->string('chapter_name');
            $table->string('code_v1');
            $table->unsignedBigInteger('group_id');
            $table->integer('order');
            $table->string('text_imlaei_simple');
            $table->string('text_uthmani');

            $table->foreign('group_id')->references('id')->on('mutashabihs_groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutashabihs');
    }
};
