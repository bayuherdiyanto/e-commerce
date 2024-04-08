<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'com-keranjang';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'kode',
        'tanggal',
        'nama',
        'kode_pelanggan',
        'no_telepon',
        'alamat',
        'total',
        'status'
    ];
}
