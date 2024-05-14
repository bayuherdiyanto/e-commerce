<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'sma_units';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
}
