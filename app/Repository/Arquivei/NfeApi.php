<?php

namespace App\Repository\Arquivei;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Support\Facades\Http;

class NfeApi
{
    private $id;
    private $key;
    private $url;

    public function __construct()
    {
        $this->id   = env('ARQUIVEI_ID');
        $this->key  = env('ARQUIVEI_KEY');
        $this->url  = env('ARQUIVEI_URL') . "/v1/nfe/";
    }

    public function receiveAll($cursor)
    {
        return json_decode(Http::withHeaders([
            "Content-Type"  => "application/json",
            "x-api-id"      => $this->id,
            "x-api-key"     => $this->key,
        ])->get("{$this->url}received?cursor={$cursor}&limit=50"));
    }

    public function findByAccessKeyAndReceive(string $accessKey): object
    {
        return json_decode(Http::withHeaders([
            "Content-Type"  => "application/json",
            "x-api-id"      => $this->id,
            "x-api-key"     => $this->key,
        ])->get("{$this->url}received?access_key[]={$accessKey}"));
    }
}
