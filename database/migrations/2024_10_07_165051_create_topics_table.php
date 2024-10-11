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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên chủ để');
            $table->string('tags')->nullable()->comment('tags');
            $table->text('meta_title')->nullable()->comment('meta_title');
            $table->text('meta_keywords')->nullable()->comment('meta_keywords');
            $table->text('meta_description')->nullable()->comment('meta_description');
            $table->text('canonical_url')->nullable()->comment('canonical_url');
            $table->boolean('status')->comment('Trạng thái');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
