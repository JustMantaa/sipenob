<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_id',
        'obat_id',
        'jumlah',
        'harga_beli',
        'subtotal',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
