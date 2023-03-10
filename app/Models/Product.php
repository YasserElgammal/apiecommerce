<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['market_id','name','price', 'desc', 'status'];

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