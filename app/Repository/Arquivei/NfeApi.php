<?php

namespace App\Repository\Arquivei;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Support\Facades\Http;

class NfeApi {
    private $authorization;
    private $url;

    public function __construct() {
        $this->id   = env('ARQUIVEI_ID');
        $this->key  = env('ARQUIVEI_KEY');
        $this->url  = env('ARQUIVEI_URL')."/v1/nfe/";
    }

    public function received() {
        return json_decode(Http::withHeaders([
            "x-api-id" => $this->authorization,
            "x-api-key" => $this->authorization,
        ])->get("{$this->url}received/")->body());
    }
}
