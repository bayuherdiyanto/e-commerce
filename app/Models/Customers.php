<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'sma_companies';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    // protected $fillable = [
    //     'image1'
    // ];
}
