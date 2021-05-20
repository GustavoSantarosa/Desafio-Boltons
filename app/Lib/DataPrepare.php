<?php

namespace App\Lib;

final class dataPrepare
{
    public static function makeMessage($success, $message, $code, $data = null)
    {
        $retArr = array(
            "success" => $success,
            "code" => $code,
            "msg"  => $message,
        );

        if (isset($data->id)) {
            $retArr['data'] = $data;
        }

        return $retArr;
    }
}
