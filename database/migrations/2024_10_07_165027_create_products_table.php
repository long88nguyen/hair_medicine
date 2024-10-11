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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên sản phẩm');
            $table->string('slug')->comment('Đường dẫn')->unique();
            $table->unsignedBigInteger('category_id')->comment('category.id');
            $table->unsignedBigInteger('brand_id')->comment('brand.id');
            $table->longText('description')->nullable()->comment('Mô tả sản phẩm');
            $table->integer('price')->nullable()->comment('Giá sản phẩm');
            $table->boolean('status')->nullable()->default(0)->comment('Trạng thái');
            $table->string('discount_type')->nullable()->comment('Loại giảm giá');
            $table->integer('discount')->nullable()->comment('giảm giá');
            $table->integer('quantity')->nullable()->comment('số lượng trong kho');
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
        Schema::dropIfExists('products');
    }
};
