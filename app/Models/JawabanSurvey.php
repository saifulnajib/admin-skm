<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSurvey extends Model
{
    use HasFactory;

    protected $table = 'jawaban_survey';
    protected $fillable = ['id_survey','id_responden','id_pilihan_jawaban','bobot'];

    public function getPilihanJawaban(){
        return $this->hasOne(PilihanJawaban::class, 'id_pilihan_jawaban');
    }

    public function responden(){
        return $this->belongsTo(Responden::class, 'id_responden');
    }
}
