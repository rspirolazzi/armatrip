<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeBrandTrait;
use Tests\ApiTestTrait;

class BrandApiTest extends TestCase
{
    use MakeBrandTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_brand()
    {
        $brand = $this->fakeBrandData();
        $this->response = $this->json('POST', '/api/admin/brands', $brand);

        $this->assertApiResponse($brand);
    }

    /**
     * @test
     */
    public function test_read_brand()
    {
        $brand = $this->makeBrand();
        $this->response = $this->json('GET', '/api/admin/brands/'.$brand->id);

        $this->assertApiResponse($brand->toArray());
    }

    /**
     * @test
     */
    public function test_update_brand()
    {
        $brand = $this->makeBrand();
        $editedBrand = $this->fakeBrandData();

        $this->response = $this->json('PUT', '/api/admin/brands/'.$brand->id, $editedBrand);

        $this->assertApiResponse($editedBrand);
    }

    /**
     * @test
     */
    public function test_delete_brand()
    {
        $brand = $this->makeBrand();
        $this->response = $this->json('DELETE', '/api/admin/brands/'.$brand->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/admin/brands/'.$brand->id);

        $this->response->assertStatus(404);
    }
}
