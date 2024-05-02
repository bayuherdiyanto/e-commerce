<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaProductQty extends Model
{
    protected $table = 'sma_harga_product_qty';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
}
