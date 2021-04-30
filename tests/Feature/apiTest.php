<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class apiTest extends TestCase
{
    /**
     * @test
     */
    public function check_online_api()
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function check_structure_api_route()
    {
        $this->json('get', '/api/v1/nf/chave/50171130290824000104550010000224381005443300?nocache=1', [], [
            "Content-Type"  => "application/json",
            "x-api-id"      => "1234",
            "x-api-key"     => "5678"
        ])
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(
            [
                "success",
                "code",
                "msg",
                "data"     => [
                    "chnfe",
                    "nnf",
                    "vnf"
                ]
            ]
         );
    }

    /**
     * @test
     */
    public function check_exact_return_api_route()
    {
        $this->json('get', '/api/v1/nf/chave/50171130290824000104550010000224381005443300?nocache=1', [], [
            "Content-Type"  => "application/json",
            "x-api-id"      => "1234",
            "x-api-key"     => "5678"
        ])
        ->assertStatus(Response::HTTP_OK)
        ->assertExactJson(
            [
                "success"  => true,
                "code"     => "2000",
                "msg"      => "A nota com a chave de acesso '50171130290824000104550010000224381005443300' foi localizada com sucesso!",
                "data"     => [
                    "chnfe" => "50171130290824000104550010000224381005443300",
                    "nnf"   => "22438",
                    "vnf"   => "1348.00"
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function check_value_return_api_route()
    {
        $apiResponse = $this->json('get', '/api/v1/nf/chave/50171130290824000104550010000224381005443300?nocache=1', [], [
            "Content-Type"  => "application/json",
            "x-api-id"      => "1234",
            "x-api-key"     => "5678"
        ])
        ->getContent();

        $nfData = json_decode($apiResponse, true)['data'];

        $total = $nfData["vnf"] - 1348.00;

        $this->assertEquals(0,  $total);
    }

}
