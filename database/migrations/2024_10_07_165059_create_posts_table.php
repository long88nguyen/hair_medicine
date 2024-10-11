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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Tên bài viết');
            $table->unsignedInteger('topic_id')->comment('topic.id');
            $table->longText('content')->nullable()->comment('Nội dung bài viết');
            $table->string('slug')->nullable()->comment('đường dẫn');
            $table->text('description')->nullable()->comment('mô tả bài viết');
            $table->string('product_ids')->nullable()->comment('Các sản phẩm có liên quan');
            $table->string('tags')->nullable()->comment('tags');
            $table->text('meta_title')->nullable()->comment('meta_title');
            $table->text('meta_keywords')->nullable()->comment('meta_keywords');
            $table->text('meta_description')->nullable()->comment('meta_description');
            $table->text('canonical_url')->nullable()->comment('canonical_url');
            $table->unsignedInteger('created_by')->nullable()->comment('người tạo');
            $table->unsignedInteger('updated_by')->nullable()->comment('người sửa cuối');
            $table->unsignedInteger('deleted_by')->nullable()->comment('người xóa');
            $table->timestamp('deleted_at')->nullable()->comment('thời gian xóa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
