<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoDetail extends Model
{
    protected $table = 'sma_diskon_bertingkat_items';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
}
