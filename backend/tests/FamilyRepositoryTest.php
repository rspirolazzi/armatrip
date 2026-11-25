<?php

use App\Models\Family;
use App\Repositories\FamilyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FamilyRepositoryTest extends TestCase
{
    use MakeFamilyTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FamilyRepository
     */
    protected $familyRepo;

    public function setUp()
    {
        parent::setUp();
        $this->familyRepo = App::make(FamilyRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFamily()
    {
        $family = $this->fakeFamilyData();
        $createdFamily = $this->familyRepo->create($family);
        $createdFamily = $createdFamily->toArray();
        $this->assertArrayHasKey('id', $createdFamily);
        $this->assertNotNull($createdFamily['id'], 'Created Family must have id specified');
        $this->assertNotNull(Family::find($createdFamily['id']), 'Family with given id must be in DB');
        $this->assertModelData($family, $createdFamily);
    }

    /**
     * @test read
     */
    public function testReadFamily()
    {
        $family = $this->makeFamily();
        $dbFamily = $this->familyRepo->find($family->id);
        $dbFamily = $dbFamily->toArray();
        $this->assertModelData($family->toArray(), $dbFamily);
    }

    /**
     * @test update
     */
    public function testUpdateFamily()
    {
        $family = $this->makeFamily();
        $fakeFamily = $this->fakeFamilyData();
        $updatedFamily = $this->familyRepo->update($fakeFamily, $family->id);
        $this->assertModelData($fakeFamily, $updatedFamily->toArray());
        $dbFamily = $this->familyRepo->find($family->id);
        $this->assertModelData($fakeFamily, $dbFamily->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFamily()
    {
        $family = $this->makeFamily();
        $resp = $this->familyRepo->delete($family->id);
        $this->assertTrue($resp);
        $this->assertNull(Family::find($family->id), 'Family should not exist in DB');
    }
}
