<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingOrder extends Model
{
    protected $table = 'sma_quotes';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'date',
        'reference_no',
        'customer_id',
        'customer',
        'warehouse_id',
        'warehouse',
        'biller_id',
        'biller',
        'note',
        'internal_note',
        'total',
        'product_discount',
        'order_discount',
        'order_discount_id',
        'total_discount',
        'product_tax',
        'order_tax_id',
        'order_tax',
        'total_tax',
        'shipping',
        'grand_total',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'attachment',
        'supplier_id',
        'supplier',
        'hash',
        'cgst',
        'sgst',
        'igst',
        'type',
        'marketing_id',
        'marketing',
        'max_kirim_barang',
        'tgl_po_kirim',
        'tax_metod_pembelian',
        'tax_metod_penjualan',
        'id_tax_pembelian',
        'id_tax_penjualan',
        'due_date',
        'ongkos_palet',
        'payment_term',
        'footer_pmp_info_purchase',
        'term_condition',
        'alamat_kirim_supplier',
        'ekspedisi',
        'ekspedisi_id',
        'purchase_id',
        'purchase_reference_no'
    ];
}
