<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = ['market_id','name','price', 'desc', 'status'];
    public $translatable = ['name', 'desc'];


    // Product belongs to one Market
    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
