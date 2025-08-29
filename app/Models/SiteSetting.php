<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $table = 'site_settings';
    protected $fillable = ['name','nama_aplikasi','deskripso',
                            'tentang','deskripsi_unsur','logo',
                            'telp','email','copyright'];
}
