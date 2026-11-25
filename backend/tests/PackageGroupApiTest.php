<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakePackageGroupTrait;
use Tests\ApiTestTrait;

class PackageGroupApiTest extends TestCase
{
    use MakePackageGroupTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_package_group()
    {
        $packageGroup = $this->fakePackageGroupData();
        $this->response = $this->json('POST', '/api/admin/packageGroups', $packageGroup);

        $this->assertApiResponse($packageGroup);
    }

    /**
     * @test
     */
    public function test_read_package_group()
    {
        $packageGroup = $this->makePackageGroup();
        $this->response = $this->json('GET', '/api/admin/packageGroups/'.$packageGroup->id);

        $this->assertApiResponse($packageGroup->toArray());
    }

    /**
     * @test
     */
    public function test_update_package_group()
    {
        $packageGroup = $this->makePackageGroup();
        $editedPackageGroup = $this->fakePackageGroupData();

        $this->response = $this->json('PUT', '/api/admin/packageGroups/'.$packageGroup->id, $editedPackageGroup);

        $this->assertApiResponse($editedPackageGroup);
    }

    /**
     * @test
     */
    public function test_delete_package_group()
    {
        $packageGroup = $this->makePackageGroup();
        $this->response = $this->json('DELETE', '/api/admin/packageGroups/'.$packageGroup->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/admin/packageGroups/'.$packageGroup->id);

        $this->response->assertStatus(404);
    }
}
