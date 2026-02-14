<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelasionalObat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    public function obats()
    {
        return $this->hasMany(Obat::class);
    }
}
