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
    private $Produto;

    public function __construct(Nfe $Nfe){
        $this->Nfe = $Nfe;
    }

    /**
     * endpoint apenas para visualizar as notas que constam na arquivei
     */
    public function index(){
        try{
            $NfeApi = new NfeApi();
            $NfeApiCallback = $NfeApi->received();

            if($NfeApiCallback->status->code != 200){
                return response()->json([
                    "success"   => false
                ]);
                return response()->json(DataPrepare::errorMessage(
                    "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                    ErrorCodes::COD_SISTEMA_FORA
                ), 200);
            }


            return response()->json(dataPrepare::successMessage(
                "Notas buscadas com sucesso",
                ErrorCodes::COD_ENVIADO_SUCESSO,
                $NfeApiCallback->data
            ), 200);
        }catch(exception $e){
            if(!config("app.debug")){
                return response()->json(DataPrepare::errorMessage(
                    "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            }else{
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
    public function show(Request $Request, $Access_key){
        try{
            $Nfe = $this->Nfe->select()->where("chnfe", $Access_key)->first();

            if(!$Nfe || $Request->nocache){
                $NfeApi = new NfeApi();
                $NfeApiCallback = $NfeApi->received($Access_key);

                if($NfeApiCallback->status->code != 200){
                    if(config("app.debug")){
                        return response()->json($NfeApiCallback, 200);
                    }else{
                        return response()->json(DataPrepare::errorMessage(
                            "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                            ErrorCodes::COD_SISTEMA_FORA
                        ), 200);
                    }
                }

                $xml = json_decode(json_encode(simplexml_load_string(base64_decode($NfeApiCallback->data[0]->xml))));

                $NfeData = [
                    "chnfe" => $Access_key,
                    "nnf"   => $xml->NFe->infNFe->ide->nNF,
                    "vnf"   => $xml->NFe->infNFe->total->ICMSTot->vNF,
                ];

                if(!$Nfe){
                    $this->Nfe->create([
                        "chnfe" => $Access_key,
                        "nnf"   => $xml->NFe->infNFe->ide->nNF,
                        "vnf"   => $xml->NFe->infNFe->total->ICMSTot->vNF,
                        "xml"   => $NfeApiCallback->data[0]->xml
                    ]);
                }else{
                    $Nfe = $this->Nfe->find($Nfe->id);
                    $Nfe->update(arraY_merge($NfeData,[
                        "xml"   => $NfeApiCallback->data[0]->xml
                    ]));
                }
            }else{
                $NfeData = [
                    "chnfe" => $Nfe->chnfe,
                    "nnf"   => $Nfe->nnf,
                    "vnf"   => $Nfe->vnf,
                ];
            }

            return response()->json(dataPrepare::successMessage(
                "A nota com a chave de acesso '{$Access_key}' foi localizada com sucesso!",
                ErrorCodes::COD_ENVIADO_SUCESSO,
                $NfeData
            ), 200);
        }catch(exception $e){
            if(!config("app.debug")){
                return response()->json(DataPrepare::errorMessage(
                    "A api esta temporariamente em manutenção, tente novamente mais tarde!",
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            }else{
                return response()->json(DataPrepare::errorMessage(
                    $e->getMessage(),
                    ErrorCodes::COD_ERRO_NAO_IDENTIFICADO
                ), 500);
            }
        }
    }
}
