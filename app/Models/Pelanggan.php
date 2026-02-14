<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggan',
        'alamat',
        'telepon',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}
