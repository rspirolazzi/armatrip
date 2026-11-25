<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MercadoPagoApiTest extends TestCase
{
    use MakeMercadoPagoTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMercadoPago()
    {
        $mercadoPago = $this->fakeMercadoPagoData();
        $this->json('POST', '/api/v1/mercadoPagos', $mercadoPago);

        $this->assertApiResponse($mercadoPago);
    }

    /**
     * @test
     */
    public function testReadMercadoPago()
    {
        $mercadoPago = $this->makeMercadoPago();
        $this->json('GET', '/api/v1/mercadoPagos/'.$mercadoPago->id);

        $this->assertApiResponse($mercadoPago->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMercadoPago()
    {
        $mercadoPago = $this->makeMercadoPago();
        $editedMercadoPago = $this->fakeMercadoPagoData();

        $this->json('PUT', '/api/v1/mercadoPagos/'.$mercadoPago->id, $editedMercadoPago);

        $this->assertApiResponse($editedMercadoPago);
    }

    /**
     * @test
     */
    public function testDeleteMercadoPago()
    {
        $mercadoPago = $this->makeMercadoPago();
        $this->json('DELETE', '/api/v1/mercadoPagos/'.$mercadoPago->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/mercadoPagos/'.$mercadoPago->id);

        $this->assertResponseStatus(404);
    }
}
