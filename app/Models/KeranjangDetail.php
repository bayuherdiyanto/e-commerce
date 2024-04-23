<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangDetail extends Model
{
    protected $table = 'com-detail_keranjang';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_keranjang',
        'item_id',
        'item_name',
        'harga',
        'diskon',
        'qty',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'item_id', 'id');
    }
}
