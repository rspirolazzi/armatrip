<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderItemTagApiTest extends TestCase
{
    use MakeOrderItemTagTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateOrderItemTag()
    {
        $orderItemTag = $this->fakeOrderItemTagData();
        $this->json('POST', '/api/v1/orderItemTags', $orderItemTag);

        $this->assertApiResponse($orderItemTag);
    }

    /**
     * @test
     */
    public function testReadOrderItemTag()
    {
        $orderItemTag = $this->makeOrderItemTag();
        $this->json('GET', '/api/v1/orderItemTags/'.$orderItemTag->id);

        $this->assertApiResponse($orderItemTag->toArray());
    }

    /**
     * @test
     */
    public function testUpdateOrderItemTag()
    {
        $orderItemTag = $this->makeOrderItemTag();
        $editedOrderItemTag = $this->fakeOrderItemTagData();

        $this->json('PUT', '/api/v1/orderItemTags/'.$orderItemTag->id, $editedOrderItemTag);

        $this->assertApiResponse($editedOrderItemTag);
    }

    /**
     * @test
     */
    public function testDeleteOrderItemTag()
    {
        $orderItemTag = $this->makeOrderItemTag();
        $this->json('DELETE', '/api/v1/orderItemTags/'.$orderItemTag->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/orderItemTags/'.$orderItemTag->id);

        $this->assertResponseStatus(404);
    }
}
