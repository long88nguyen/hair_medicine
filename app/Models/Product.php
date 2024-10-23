<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "category_id",
        "brand_id",
        "description",
        "price",
        "status",
        "discount_type",
        "discount",
        "quantity",
        "tags",
        "meta_title",
        "meta_keywords",
        "meta_description",
        "canonical_url",
        "created_by",
        "updated_by",
        "deleted_by",
        "deleted_at",
        "created_at",
        "updated_at",
    ];

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function product_image_main()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id')->where('is_main',1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}