<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'survey';
    protected $fillable = ['name','id_layanan_opd','start_date','end_date','nilai','is_active','keterangan'];

    public function getLayananOpd(){
        return $this->belongsTo(LayananOpd::class, 'id_layanan_opd');
    }

     public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'id_survey');
    }
}
