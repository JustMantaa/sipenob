<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'relasional_obat_id',
        'kode_obat',
        'nama_obat',
        'deskripsi',
        'stok',
        'harga_beli',
        'harga_jual',
        'tanggal_kadaluarsa',
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
    ];

    public function relasionalObat()
    {
        return $this->belongsTo(RelasionalObat::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'obat_supplier');
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
