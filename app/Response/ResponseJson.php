<?php

namespace App\Response;


class ResponseJson{
    public function responseSuccess($data, $message = null, $code = 200){
        if ($data == null){
            return response()->json([
                'message' => $message,
                'code' => $code,
                'status' => 'success'
            ], $code);
        } else {
            return response()->json([
                'message' => $message,
                'code' => $code,
                'status' => 'success',
                'data' => $data
            ], $code);
        }
    }

    public function responseError($message = null, $code = 500){
        return response()->json([
            'message' => $message,
            'code' => $code,
            'status' => 'failed'
        ], $code);
    }


}