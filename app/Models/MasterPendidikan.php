<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPendidikan extends Model
{
    use HasFactory;


    protected $table = 'master_pendidikan';
    protected $fillable = ['name','singkatan','is_active'];
}
