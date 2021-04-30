<?php

namespace App\Http\Middleware;

use Closure;
use App\Lib\DataPrepare;
use App\Lib\ErrorCodes;
use Illuminate\Http\Request;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->header('Content-Type') != "application/json"){
            return response()->json(DataPrepare::errorMessage(
                "Bad Request. Invalid Content-Type.",
                ErrorCodes::COD_HEADER_NO_JSON
            ), 400);
        }

        if(!$request->header('x-api-id')){
            return response()->json(DataPrepare::errorMessage(
                "Bad Request. Configure your X-API-ID in header and try again.",
                ErrorCodes::COD_HEADER_NO_ID
            ), 400);
        }

        if(!$request->header('x-api-key')){
            return response()->json(DataPrepare::errorMessage(
                "Bad Request. Configure your X-API-KEY in header and try again.",
                ErrorCodes::COD_HEADER_NO_KEY
            ), 400);
        }
        echo env('x-api-id');exit;
        if($request->header('x-api-id')==env('x-api-id') && $request->header('x-api-key')==env('x-api-key')){
            return $next($request);
        }
        else{
            return response()->json(DataPrepare::errorMessage(
                "Unauthorized. Invalid X-API-KEY or X-API-ID.",
                ErrorCodes::COD_HEADER_NO_KEY
            ), 401);
        }
    }
}
