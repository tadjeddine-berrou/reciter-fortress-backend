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
        Schema::create('mutashabih_verse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mutashabih_id');
            $table->unsignedBigInteger('verse_id');

            $table->foreign("mutashabih_id")->references("id")->on("mutashabihs")->onDelete('cascade');
            $table->foreign("verse_id")->references("id")->on("verses")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutashabih_verses');
    }
};
