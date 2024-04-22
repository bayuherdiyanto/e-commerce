<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmaSetting extends Model
{
    protected $table = 'sma_settings';
    protected $primaryKey = 'setting_id';
    // public $incrementing = false;
    public $timestamps = false;
    // protected $fillable = [
    //     'gudang',
    //     'no_wa'
    // ];
}
