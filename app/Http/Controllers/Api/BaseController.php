<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //for send response
    public function sendResponse($result,$message){
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response,200);
    }

    public function sendError($error, $message=[], $code = 404){
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessage)){
            $response['data'] = $message;
        }
        return response()->json($response, $code);
    }
}
