<?php
namespace App\Lib;

abstract class ErrorCodes {
    const COD_ENVIADO_SUCESSO               = '2000'; # Enviado com sucesso
    const COD_SISTEMA_FORA                  = '3010'; # Sistema Fora do ar
    const COD_PARAMETROS_NAO_ESPERADOS      = '3020'; # Parâmetros não esperados
    const COD_INFO_EXISTE_SISTEMA           = '3030'; # Informação já existe no sistema
    const COD_ERRO_NAO_IDENTIFICADO         = '4040'; # Erro não identificado
    const COD_HEADER_NO_JSON                = '4041'; # Header não possui Content Json
    const COD_HEADER_NO_ID                  = '4042'; # Header não possui o id
    const COD_HEADER_NO_KEY                 = '4043'; # Header não possui a key
    const COD_ACCESS_UNAUTHORIZED           = '4044'; # Acesso não autorizado
}
