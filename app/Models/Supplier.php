<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'obat_supplier');
    }
}
