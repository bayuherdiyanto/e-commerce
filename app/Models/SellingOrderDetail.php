<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingOrderDetail extends Model
{
    protected $table = 'sma_quote_items';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'quote_id',
        'product_id',
        'product_code',
        'product_name',
        'product_type',
        'option_id',
        'net_unit_price',
        'unit_price',
        'quantity',
        'warehouse_id',
        'item_tax',
        'tax_rate_id',
        'tax',
        'discount',
        'discount2',
        'discount3',
        'discount4',
        'item_discount',
        'subtotal',
        'serial_no',
        'real_unit_price',
        'product_unit_id',
        'product_unit_code',
        'unit_quantity',
        'gst',
        'cgst',
        'sgst',
        'igst',
        'unit_quantity_order',
        'ongkir'
    ];
}
