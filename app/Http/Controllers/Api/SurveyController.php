<?php

namespace App\Http\Controllers\Api;

use Exception;
use Validator;
use Illuminate\Http\Request;
use App\Models\MasterPendidikan;
use App\Models\MasterPekerjaan;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
// use App\Http\Resources\Base\BaseCollection;
// use App\Http\Resources\TingkatPendidikanResource;

class SurveyController extends ApiController
{
    
    public function PendidikanOption(Request $request): JsonResponse
    {
        $data = MasterPendidikan::select('id', 'name','singkatan')->where('is_active', 1)->latest('updated_at')->get();
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

    public function PekerjaanOption(Request $request): JsonResponse
    {
        $data = MasterPekerjaan::select('id', 'name')->where('is_active', 1)->latest('updated_at')->get();
        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

}
