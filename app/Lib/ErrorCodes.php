<?php

namespace App\Lib;

abstract class ErrorCodes
{
    const CODE_SENT_SUCCESS                  = '2000'; # Sent with success
    const CODE_SYSTEM_OUT                    = '3010'; # Offline system
    const CODE_UNIDENTIFIED_ERROR            = '4040'; # Unidentified error
    const CODE_HEADER_NO_JSON                = '4041'; # Header doesn't have Content Json
    const CODE_HEADER_NO_ID                  = '4042'; # Header does not have the id
    const CODE_HEADER_NO_KEY                 = '4043'; # Header does not have the key
    const CODE_ACCESS_UNAUTHORIZED           = '4044'; # Unauthorized access
}
