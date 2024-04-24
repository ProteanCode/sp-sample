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
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->string('filename', 128);
            $table->string('hash', 128)->unique();
            $table->string('disk', 16);
            $table->text('path');
            $table->string('extension', 8);
            $table->integer('width');
            $table->integer('height');
            $table->integer('size_in_bytes');

            $table->foreignId('image_id')
                ->comment('Id of parent image, used to aggregate subimages, like miniatures')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
