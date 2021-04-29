<?php
namespace App\Lib;

final class dataPrepare {
    private static $_forbidenNames = ['code', 'msg', 'data', 'success'];

    private static function getMessage($success, $message, $code, $data=[]) {
        $retArr = array(
            "success" => $success,
            "code" => $code,
            "msg"  => $message,
        );

        if(is_array($data) && count($data) > 0){
            $retArr['data'] = $data;
        }

        return $retArr;
    }

    public static function successMessage($message, $code, $data=[]) {
        return dataPrepare::getMessage(
            true,
            $message,
            $code,
            $data
        );
    }

    public static function errorMessage($message, $code, $data=[]) {
        return dataPrepare::getMessage(
            false,
            $message,
            $code,
            $data
        );
    }
}
