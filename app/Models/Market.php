<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'vat_included', 'vat'];

    // Market belongs to one user only if Merchant
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
