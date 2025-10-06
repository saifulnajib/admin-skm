<?php

namespace App\Http\Controllers\Api;

use Exception;
use Validator;
use Illuminate\Http\Request;
use App\Models\MasterPendidikan;
use App\Models\MasterPekerjaan;
use App\Models\MasterOpd;
use App\Models\LayananOpd;
use App\Models\SiteSetting;
use App\Models\Pertanyaan;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\SurveyOptionResource;

class SurveyController extends ApiController
{
    
    public function PendidikanOption(Request $request): JsonResponse
    {
        $data = MasterPendidikan::select('id', 'name','singkatan')->where('is_active', 1)->get();
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

    public function PekerjaanOption(Request $request): JsonResponse
    {
        $data = MasterPekerjaan::select('id', 'name')->where('is_active', 1)->latest('updated_at')->get();
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

    public function OpdOption(Request $request): JsonResponse
    {
        $data = MasterOpd::select('id','name')->where('is_active', 1)->get();
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }
    
    public function layananOption(Request $request): JsonResponse
    {
        $id_opd = $request->id_opd;

        if($request->has('id_opd')){
           $data = LayananOpd::select('*')->where(['is_active'=>'1','id_opd'=>$id_opd])->get();
        }else{
           $data = LayananOpd::select('*')->where(['is_active'=>'1'])->get();
        }
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

    public function surveyOption(Request $request): JsonResponse
    {
        $id_layanan_opd = $request->id_layanan_opd;
        $data = [];
        if($request->has('id_layanan_opd')){
           $data = Survey::select('*')->with(['getLayananOpd.getOpd'])->where(['is_active'=>'1','id_layanan_opd'=>$id_layanan_opd])->get();
        }
         return response()->json([
            'success' => true,
            'data' => SurveyOptionResource::collection($data),
            'message' => 'Data retrieved successfully.'
        ]);
    }

    public function siteSetting(Request $request): JsonResponse
    {
        $data = SiteSetting::select('*')->first();
        $data["file_logo"] = env('APP_URL').'/storage/'.$data['logo']; 
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

    public function getPertanyaan(Request $request): JsonResponse
    {
        $id_survey = $request->id_survey;
        $data['id_survey'] = $request->id_survey;
        
        // if($request->has('id_survey')){
        //     $data = Pertanyaan::select('*')->with(['survey','pilihanJawaban'])->where(['id_survey'=>$id_survey])->orderBy('id_indikator')->get();
        // }
        if($request->has('id_survey')){
            $data = Survey::select('*')->with(['getLayananOpd.getOpd','pertanyaan.pilihanJawaban','pertanyaan.indikator'])->where(['id'=>$id_survey])->get();
        }


        return $this->sendResponse($data, 'Data retrieved successfully.');
    }
}
