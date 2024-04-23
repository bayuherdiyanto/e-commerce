<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrices extends Model
{
    protected $table = 'sma_product_prices';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    // protected $fillable = [
    //     'gudang',
    //     'no_wa'
    // ];
}
