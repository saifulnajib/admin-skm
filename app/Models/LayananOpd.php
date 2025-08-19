<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananOpd extends Model
{
    use HasFactory;

    protected $table = 'layanan_opd';
    protected $fillable = ['name','id_opd','is_active','keterangan'];

    public function getOpd(){
        return $this->belongsTo(MasterOpd::class, 'id_opd');
    }
}
