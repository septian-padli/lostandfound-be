<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('id_category')->constrained('categories')->onDelete('cascade');
            $table->foreignUlid('id_city')->constrained('cities')->onDelete('restrict');
            $table->foreignUlid('id_province')->constrained('provinces')->onDelete('restrict');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('address');
            $table->dateTime('found_at')->nullable();
            $table->boolean('is_found')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('count_comment')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
