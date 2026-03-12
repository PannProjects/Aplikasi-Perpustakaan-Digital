<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    protected $table = 'kategoribuku';
    protected $primaryKey = 'KategoriID';

    protected $fillable = [
        'NamaKategori',
    ];

    public function buku()
    {
        return $this->belongsToMany(Buku::class, 'kategoribuku_relasi', 'KategoriID', 'BukuID');
    }
}
