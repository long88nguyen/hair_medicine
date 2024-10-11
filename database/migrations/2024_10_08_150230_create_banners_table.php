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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Tên banner');
            $table->string('position')->nullable()->comment('Vị trí đặt banner');
            $table->string('ordering')->nullable()->comment('Thứ tự');
            $table->string('url')->nullable()->comment('Đường dẫn ảnh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
