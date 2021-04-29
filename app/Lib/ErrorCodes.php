<?php
namespace App\Lib;

abstract class ErrorCodes {
    const COD_ENVIADO_SUCESSO               = '2000'; # 2010 - Enviado com sucesso
    const COD_SISTEMA_FORA                  = '3010'; # 2010 - Sistema Fora do ar
    const COD_PARAMETROS_NAO_ESPERADOS      = '3020'; # 3010 - Parâmetros não esperados
    const COD_INFO_EXISTE_SISTEMA           = '3030'; # 3030 - Informação já existe no sistema
    const COD_ERRO_NAO_IDENTIFICADO         = '4040'; # 4040 - Erro não identificado
}
