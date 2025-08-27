<?php

namespace App\Http\Controllers\Api;

use Exception;
use Validator;
use Illuminate\Http\Request;
use App\Models\MasterPendidikan;
use App\Models\MasterPekerjaan;
use App\Models\MasterOpd;
use App\Models\LayananOpd;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
// use App\Http\Resources\Base\BaseCollection;
// use App\Http\Resources\TingkatPendidikanResource;

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

}
