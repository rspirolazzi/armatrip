<?php namespace Tests\Repositories;

use App\Models\Admin\PackageGroup;
use App\Repositories\Admin\PackageGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakePackageGroupTrait;
use Tests\ApiTestTrait;

class PackageGroupRepositoryTest extends TestCase
{
    use MakePackageGroupTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PackageGroupRepository
     */
    protected $packageGroupRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->packageGroupRepo = \App::make(PackageGroupRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_package_group()
    {
        $packageGroup = $this->fakePackageGroupData();
        $createdPackageGroup = $this->packageGroupRepo->create($packageGroup);
        $createdPackageGroup = $createdPackageGroup->toArray();
        $this->assertArrayHasKey('id', $createdPackageGroup);
        $this->assertNotNull($createdPackageGroup['id'], 'Created PackageGroup must have id specified');
        $this->assertNotNull(PackageGroup::find($createdPackageGroup['id']), 'PackageGroup with given id must be in DB');
        $this->assertModelData($packageGroup, $createdPackageGroup);
    }

    /**
     * @test read
     */
    public function test_read_package_group()
    {
        $packageGroup = $this->makePackageGroup();
        $dbPackageGroup = $this->packageGroupRepo->find($packageGroup->id);
        $dbPackageGroup = $dbPackageGroup->toArray();
        $this->assertModelData($packageGroup->toArray(), $dbPackageGroup);
    }

    /**
     * @test update
     */
    public function test_update_package_group()
    {
        $packageGroup = $this->makePackageGroup();
        $fakePackageGroup = $this->fakePackageGroupData();
        $updatedPackageGroup = $this->packageGroupRepo->update($fakePackageGroup, $packageGroup->id);
        $this->assertModelData($fakePackageGroup, $updatedPackageGroup->toArray());
        $dbPackageGroup = $this->packageGroupRepo->find($packageGroup->id);
        $this->assertModelData($fakePackageGroup, $dbPackageGroup->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_package_group()
    {
        $packageGroup = $this->makePackageGroup();
        $resp = $this->packageGroupRepo->delete($packageGroup->id);
        $this->assertTrue($resp);
        $this->assertNull(PackageGroup::find($packageGroup->id), 'PackageGroup should not exist in DB');
    }
}
