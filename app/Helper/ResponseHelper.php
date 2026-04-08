<?php

if (!function_exists('successResponse')) {

    function successResponse($data = [], $message = 'Request processed successfully', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}

if (!function_exists('errorResponse')) {

    function errorResponse($message = 'Request failed', $code = 400, $data = [])
    {
        return response()->json([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

}

if (!function_exists('formatPaginatedData')){
    function formatPaginatedData ($paginatedData , $formatedData , $key = "data"){
           return [
                $key => $formatedData,
                'pagination' => [
                    'current_page' => $paginatedData->currentPage()??'',
                    'per_page' => $paginatedData->perPage()??'',
                    'total' => $paginatedData->total()??'',
                    'last_page' => $paginatedData->lastPage()??'',
                ]
            ];
    }
}

?>