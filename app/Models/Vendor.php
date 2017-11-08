<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'lux_vendor';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Products::class, 'vendor_id', 'id');
    }
}
