<?php

namespace App\Http\Controllers\Api;

use exception;
use App\Lib\ErrorCodes;
use App\Lib\XmlPrepare;
use App\Lib\DataPrepare;
use App\Jobs\receiveNfes;
use Illuminate\Http\Request;
use App\Models\Faturamento\Nfe;
use App\Repository\Arquivei\NfeApi;
use App\Http\Controllers\Controller;

class NfController extends Controller
{
    private $nfe;

    public function __construct(Nfe $nfe)
    {
        $this->nfe = $nfe;
    }

    public function index()
    {
        try {
            $cursor = 0;

            do {
                $nfeApi = new NfeApi();
                $nfeApiCallback = $nfeApi->receiveAll($cursor);

                if ($nfeApiCallback->status->code != 200) {
                    return response()->json(DataPrepare::makeMessage(
                        false,
                        "The api is temporarily under maintenance, please try again later!",
                        ErrorCodes::CODE_SYSTEM_OUT
                    ), 200);
                }

                receiveNfes::dispatch($nfeApiCallback->data, $this->nfe)->delay(now()->addSeconds('1'));
                $cursor += 50;
            } while ($nfeApiCallback->count == 50);

            return response()->json(dataPrepare::makeMessage(
                true,
                "Notes received successfully",
                ErrorCodes::CODE_SENT_SUCCESS,
            ), 200);
        } catch (exception $e) {
            if (!config("app.debug")) {
                return response()->json(DataPrepare::makeMessage(
                    false,
                    "The api is temporarily under maintenance, please try again later!",
                    ErrorCodes::CODE_UNIDENTIFIED_ERROR
                ), 500);
            } else {
                return response()->json(DataPrepare::makeMessage(
                    false,
                    $e->getMessage(),
                    ErrorCodes::CODE_UNIDENTIFIED_ERROR
                ), 500);
            }
        }
    }

    public function show(Request $request, $accessKey)
    {
        try {
            $nfe = $this->nfe->findByChnfe($accessKey);

            if (!$nfe || $request->noCache) {
                $nfeApi = new NfeApi();
                $nfeApiCallback = $nfeApi->findByAccessKeyAndReceive($accessKey);

                if ($nfeApiCallback->status->code != 200) {
                    if (config("app.debug")) {
                        return response()->json($nfeApiCallback, 200);
                    } else {
                        return response()->json(DataPrepare::makeMessage(
                            false,
                            "The api is temporarily under maintenance, please try again later!",
                            ErrorCodes::CODE_SYSTEM_OUT
                        ), 200);
                    }
                }

                $xmlPrepare = new XmlPrepare($nfeApiCallback->data[0]->xml, $accessKey);

                if (!$nfe) {
                    $nfe = $this->nfe->create([
                        "chnfe" => $xmlPrepare->chnfe,
                        "vnf"   => $xmlPrepare->vnf
                    ]);
                } else {
                    $nfe->chnfe = $xmlPrepare->chnfe;
                    $nfe->vnf   = $xmlPrepare->vnf;
                    $nfe->update();
                }
            }

            return response()->json(dataPrepare::makeMessage(
                true,
                "The note with access key '{$accessKey}' was successfully located!",
                ErrorCodes::CODE_SENT_SUCCESS,
                $nfe
            ), 200);
        } catch (exception $e) {
            if (!config("app.debug")) {
                return response()->json(DataPrepare::makeMessage(
                    false,
                    "The api is temporarily under maintenance, please try again later!",
                    ErrorCodes::CODE_UNIDENTIFIED_ERROR
                ), 500);
            } else {
                return response()->json(DataPrepare::makeMessage(
                    false,
                    $e->getMessage(),
                    ErrorCodes::CODE_UNIDENTIFIED_ERROR
                ), 500);
            }
        }
    }
}
