<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'         => $this->name,
            'id_layanan_opd' => $this->getLayananOpd->id ?? null,
            'layanan_opd' => $this->getLayananOpd->name ?? null,
            'id_opd'        => $this->getLayananOpd->getOpd->id ?? null,
            'opd'        => $this->getLayananOpd->getOpd->name ?? null,
            'periode'    => $this->start_date . ' s.d ' . $this->end_date,
            'is_active'  => $this->is_active,
            'nilai'  => $this->nilai,
            'keterangan'  => $this->keterangan,
        ];
    }
}
