<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilScrapper extends Model
{
    protected $table = 'hasil_scrapper';
    protected $primaryKey = 'hasil_scrapper_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul', 'isi_berita', 'dibuat_pada'
    ];
}
