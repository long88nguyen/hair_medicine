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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên danh mục');
            $table->text('description')->nullable()->comment('Mô tả danh mục');
            $table->text('image')->nullable()->comment('Ảnh danh mục');
            $table->text('slug')->nullable()->comment('Đường dẫn');
            $table->string('tags')->nullable()->comment('tags');
            $table->text('meta_title')->nullable()->comment('meta_title');
            $table->text('meta_keywords')->nullable()->comment('meta_keywords');
            $table->text('meta_description')->nullable()->comment('meta_description');
            $table->text('canonical_url')->nullable()->comment('canonical_url');
            $table->timestamp('deleteted_at')->nullable()->comment('Thời gian xóa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
