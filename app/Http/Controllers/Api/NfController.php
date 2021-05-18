<?php

namespace App\Http\Controllers\Api;

use App\Lib\ErrorCodes;
use App\Lib\DataPrepare;
use exception;

use App\Repository\Arquivei\NfeApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faturamento\Nfe;

class NfController extends Controller
{
    private $nfe;

    public function __construct(Nfe $nfe)
    {
        $this->nfe = $nfe;
    }

    /**
     * endpoint apenas para visualizar as notas que constam na arquivei
     */
    public function index()
    {
        try {
            $nfeApi = new NfeApi();
            $nfeApiCallback = $nfeApi->received();

            if ($nfeApiCallback->status->code != 200) {
                return response()->json(DataPrepare::errorMessage(
                    "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                    ErrorCodes::COD_SISTEMA_FORA
                ), 200);
            }

            return response()->json(dataPrepare::successMessage(
                "Notas buscadas com sucesso",
                ErrorCodes::COD_ENVIADO_SUCESSO,
                $nfeApiCallback->data
            ), 200);
        } catch (exception $e) {
            if (!config("app.debug")) {
                return response()->json(DataPrepare::errorMessage(
                    "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            } else {
                return response()->json(DataPrepare::errorMessage(
                    $e->getMessage(),
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            }
        }
    }

    /**
     * endpoint solicitado no desafio
     */
    public function show(Request $request, $accessKey)
    {
        try {
            $nfe = $this->nfe->select()->where("chnfe", $accessKey)->first();

            if (!$nfe || $request->noCache) {
                $nfeApi = new NfeApi();
                $nfeApiCallback = $nfeApi->received($accessKey);

                if ($nfeApiCallback->status->code != 200) {
                    if (config("app.debug")) {
                        return response()->json($nfeApiCallback, 200);
                    } else {
                        return response()->json(DataPrepare::errorMessage(
                            "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                            ErrorCodes::COD_SISTEMA_FORA
                        ), 200);
                    }
                }

                $xml = json_decode(json_encode(simplexml_load_string(base64_decode($nfeApiCallback->data[0]->xml))));

                $nfeData = [
                    "chnfe" => $accessKey,
                    "nnf"   => $xml->NFe->infNFe->ide->nNF,
                    "vnf"   => $xml->NFe->infNFe->total->ICMSTot->vNF,
                ];

                if (!$nfe) {
                    $this->nfe->create([
                        "chnfe" => $accessKey,
                        "nnf"   => $xml->NFe->infNFe->ide->nNF,
                        "vnf"   => $xml->NFe->infNFe->total->ICMSTot->vNF,
                        "xml"   => $nfeApiCallback->data[0]->xml
                    ]);
                } else {
                    $nfe = $this->nfe->find($nfe->id);
                    $nfe->update(arraY_merge($nfeData, [
                        "xml"   => $nfeApiCallback->data[0]->xml
                    ]));
                }
            } else {
                $nfeData = [
                    "chnfe" => $nfe->chnfe,
                    "nnf"   => $nfe->nnf,
                    "vnf"   => $nfe->vnf,
                ];
            }

            return response()->json(dataPrepare::successMessage(
                "A nota com a chave de acesso '{$accessKey}' foi localizada com sucesso!",
                ErrorCodes::COD_ENVIADO_SUCESSO,
                $nfeData
            ), 200);
        } catch (exception $e) {
            if (!config("app.debug")) {
                return response()->json(DataPrepare::errorMessage(
                    "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            } else {
                return response()->json(DataPrepare::errorMessage(
                    $e->getMessage(),
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            }
        }
    }
}
