<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'UserID';

    protected $fillable = [
        'Username',
        'Password',
        'Email',
        'NamaLengkap',
        'Alamat',
        'Role',
    ];

    protected $hidden = [
        'Password',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'UserID', 'UserID');
    }

    public function ulasan()
    {
        return $this->hasMany(UlasanBuku::class, 'UserID', 'UserID');
    }

    public function koleksi()
    {
        return $this->hasMany(KoleksiPribadi::class, 'UserID', 'UserID');
    }
}
