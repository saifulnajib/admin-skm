<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';
    protected $fillable = ['name','id_survey','id_indikator','is_active','keterangan'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'id_survey');
    }

    public function pilihanJawaban()
    {
        return $this->hasMany(PilihanJawaban::class, 'id_pertanyaan');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'id_indikator');
    }

}
