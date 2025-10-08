<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responden extends Model
{
    use HasFactory;

    protected $table = 'respondens';
    protected $fillable = ['name','id_survey','id_pendidikan','id_pekerjaan','umur','gender','nilai','is_active','keterangan'];

    public function getSurvey(){
        return $this->belongsTo(Survey::class, 'id_survey');
    }

    public function getPendidikan(){
        return $this->belongsTo(MasterPendidikan::class, 'id_pendidikan');
    }

    public function getPekerjaan(){
        return $this->belongsTo(MasterPekerjaan::class, 'id_pekerjaan');
    }
}
