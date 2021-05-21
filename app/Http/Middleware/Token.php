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
        if ($request->header('Content-Type') != "application/json") {
            return response()->json(DataPrepare::makeMessage(
                false,
                "Bad Request. Invalid Content-Type.",
                ErrorCodes::CODE_HEADER_NO_JSON
            ), 400);
        }

        if (!$request->header('x-api-id')) {
            return response()->json(DataPrepare::makeMessage(
                false,
                "Bad Request. Configure your X-API-ID in header and try again.",
                ErrorCodes::CODE_HEADER_NO_ID
            ), 400);
        }

        if (!$request->header('x-api-key')) {
            return response()->json(DataPrepare::makeMessage(
                false,
                "Bad Request. Configure your X-API-KEY in header and try again.",
                ErrorCodes::CODE_HEADER_NO_KEY
            ), 400);
        }

        if ($request->header('x-api-id') != env('DESAFIO_ID') || $request->header('x-api-key') != env('DESAFIO_KEY')) {
            return response()->json(DataPrepare::makeMessage(
                false,
                "Unauthorized. Invalid X-API-KEY or X-API-ID.",
                ErrorCodes::CODE_HEADER_NO_KEY
            ), 401);
        }

        return $next($request);
    }
}
