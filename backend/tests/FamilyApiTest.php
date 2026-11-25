<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FamilyApiTest extends TestCase
{
    use MakeFamilyTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFamily()
    {
        $family = $this->fakeFamilyData();
        $this->json('POST', '/api/v1/categories', $family);

        $this->assertApiResponse($family);
    }

    /**
     * @test
     */
    public function testReadFamily()
    {
        $family = $this->makeFamily();
        $this->json('GET', '/api/v1/categories/'.$family->id);

        $this->assertApiResponse($family->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFamily()
    {
        $family = $this->makeFamily();
        $editedFamily = $this->fakeFamilyData();

        $this->json('PUT', '/api/v1/categories/'.$family->id, $editedFamily);

        $this->assertApiResponse($editedFamily);
    }

    /**
     * @test
     */
    public function testDeleteFamily()
    {
        $family = $this->makeFamily();
        $this->json('DELETE', '/api/v1/categories/'.$family->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/categories/'.$family->id);

        $this->assertResponseStatus(404);
    }
}
