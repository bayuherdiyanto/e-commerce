<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRef extends Model
{
    protected $table = 'sma_order_ref';
    protected $primaryKey = 'ref_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'so'
    ];
}
