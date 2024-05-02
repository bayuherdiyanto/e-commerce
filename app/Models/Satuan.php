<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'sma_satuan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
}
