<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Transactions;


class TransactionController extends Controller
{


    public function getTransactions()
    {
        $transactions = DB::table('transactions_view')->get();

        $response = ["status" => "Success", "data"=> $transactions];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getTransaction($transactionId)
    {
        $transaction = DB::table('transactions_view')->where('transactionId', $transactionId)->first();
        $response = ["status" => "Success", "data"=> $transaction];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }



    public function addTransaction(Request $request)
    {
        $request['entryByUserId'] = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'transactionAmount' => 'required|numeric',
            'transactionDate' => 'required|date',
            'paymentMethodId' => 'required|numeric',
            'entryByUserId' => 'required|numeric',
            'customerId' => 'required|numeric',
            'cartId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        Transactions::create($inputs);

        cacheRemove();
        $response = ["status" => "Success", "data" => "Transaction successfully saved!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function editTransaction(Request $request)
    {
        $request['entryByUserId'] = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'transactionAmount' => 'required|numeric',
            'transactionDate' => 'required|date',
            'paymentMethodId' => 'required|numeric',
            'entryByUserId' => 'required|numeric',
            'customerId' => 'required|numeric',
            'cartId' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 401);
        }

        Transactions::where('transactionId', $request->transactionId)->update($request->except(['token']));

        cacheRemove();
        $response = ["status" => "Success", "data" => "Successfully Updated !"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function deleteTransaction($transactionId)
    {
        DB::table('transactions')->where('transactionId', $transactionId)->delete();

        cacheRemove();
        $response = ["status" => "Success", "data" => "Transaction Successfully Deleted!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }




}
