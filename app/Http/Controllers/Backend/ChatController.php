<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;
use Carbon\Carbon;
use App\Messages;
use App\Events\NewMessage;

class ChatController extends Controller
{

    public function getContacts()
    {
        $contacts = DB::table('users')->where('id','!=', auth()->user()->id)->select('id', 'name', 'phone', 'email', 'photoPath')->get();
        $response = ["status" => "Success", "data"=> $contacts];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getAdminContacts()
    {
        $contacts = DB::table('admin_view')->where('isAdmin', 1)->where('id','!=', auth()->user()->id)->select('id', 'name', 'phone', 'email', 'photoPath')->get();
        $response = ["status" => "Success", "data"=> $contacts];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getMessages()
    {
        $messages = DB::table('messages')->get();
        $response = ["status" => "Success", "data"=> $messages];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function getAContactMessages($userId1, $userId2)
    {
        $messages = DB::table('messages_view')
                    ->where(
                        [
                            ['senderId', $userId1],
                            ['receiverId', $userId2],
                        ]
                    )
                    ->orWhere(
                        [
                            ['receiverId', $userId1],
                            ['senderId', $userId2],
                        ]
                    )
                    ->get();
        $response = ["status" => "Success", "data"=> $messages];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'senderId' => 'required',
            'receiverId' => 'required',
            'message' => 'required|string|min:1',
        ]);
        if ($validator->fails() ) {
            return response()->json($validator->errors(), 401);
        }

        $inputs = $request->all();
        $message = Messages::create($inputs);

        $message = DB::table('messages_view')->where('messageId', $message->messageId)->first();

        event(new NewMessage($message));

        mongodbInsertOne('messages', $message);


        $response = ["status" => "Success",  "data" => "Message successfully sent!"];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }


    public function getMongodbMessages()
    {
        $messages = mongodbFind('messages');
        $response = ["status" => "Success", "data"=> $messages];
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
    }





}
