<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\OrderItem;

class Item extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $fillable = [
        'item_name',
        'category_id',
        'price',
        'description',
        'image_path',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
