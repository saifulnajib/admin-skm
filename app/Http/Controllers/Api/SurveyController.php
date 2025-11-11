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
use App\Models\Responden;
use App\Models\JawabanSurvey;
use App\Models\Indikator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\SurveyOptionResource;
use Illuminate\Support\Facades\DB;

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

    public function jawabanSurvey(Request $request): JsonResponse
    {
        $input = $request->all();

        $messages = [
                        'gender.in' => 'Gender hanya boleh diisi dengan laki-laki atau perempuan.',
                    ];

        $validator = Validator::make($input, [
            'id_survey' => 'required',
            'id_pendidikan' => 'required',
            'id_pendidikan' => 'required',
            'umur' => 'required|numeric',
            'gender' => 'required|in:laki-laki,perempuan',
            'jawaban' => 'required|array|min:1',
            'jawaban.*.id_pilihan_jawaban' => 'required|integer|exists:pilihan_jawaban,id',
        ], $messages);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $data["id_survey"] = $request->id_survey;
        $data["id_pendidikan"] = $request->id_pendidikan;
        $data["id_pekerjaan"] = $request->id_pekerjaan;
        $data["name"] = $request->name;
        $data["umur"] = $request->umur;
        $data["gender"] = $request->gender;
        $data["keterangan"] = $request->keterangan;

        DB::beginTransaction();
        try{
            $dataResponden = Responden::create($data);
            $id_responden = $dataResponden->id;


            $insertJawaban = [];
            foreach ($input['jawaban'] as $jawaban) {
                    $insertJawaban[] = [
                        'id_survey'=>$data["id_survey"],
                        'id_responden'=>$id_responden,
                        'id_pilihan_jawaban' => $jawaban['id_pilihan_jawaban'],
                    ];
                }
            JawabanSurvey::insert($insertJawaban);
                
            DB::commit();

        return $this->sendResponse($dataResponden, 'Data retrieve succesfully.');
        } catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function indikatorOption(Request $request): JsonResponse
    {
        $data = Indikator::select('*')->where(['is_active'=>'1'])->get();

        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

}
