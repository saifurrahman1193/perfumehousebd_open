<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Formulation;


class FormulationController extends Controller
{


    public function getFormulations()
    {
        $formulations = DB::table('formulation_view')->get();

        $response = ["status" => "Success", "data"=> $formulations];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getFormulation($formulationId)
    {
        $formulation = DB::table('formulation_view')->where('formulationId', $formulationId)->first();
        $response = ["status" => "Success", "data"=> $formulation];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function addFormulation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formulation' => 'required|string|unique:formulation',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        Formulation::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "Formulation successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editFormulation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formulation' => 'required|string|unique:formulation,formulation,'.$request->formulationId.',formulationId',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Formulation::where('formulationId', $request->formulationId)->update($request->except(['token']));

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteFormulation($formulationId)
    {
        DB::table('formulation')->where('formulationId', $formulationId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Formulation Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
