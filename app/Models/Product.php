<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
    ];

    // Append custom attributes to the model's JSON form
    protected $appends = ['image_url'];

    /**
     * Relation to category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the full URL for the product image
     */
    public function getImageUrlAttribute()
    {
        // remove leading slash if it exists
    $cleanPath = ltrim($this->image, '/');

    return url('storage/' . $cleanPath);
    }
}
