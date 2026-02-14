<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_faktur',
        'supplier_id',
        'user_id',
        'tanggal_pembelian',
        'total_pembelian',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}
