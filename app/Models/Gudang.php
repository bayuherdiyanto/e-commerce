<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'sma_warehouses';
    protected $primaryKey = 'id';
    public $incrementing = true;
    // public $timestamps = false;
    // protected $fillable = [
    //     'image1'
    // ];
}
