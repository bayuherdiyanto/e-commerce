<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    protected $table = 'sma_warehouses';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    // protected $fillable = [
    //     'so'
    // ];
}
