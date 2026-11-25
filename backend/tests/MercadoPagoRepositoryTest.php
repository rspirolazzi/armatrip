<?php

use App\Models\MercadoPago;
use App\Repositories\MercadoPagoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MercadoPagoRepositoryTest extends TestCase
{
    use MakeMercadoPagoTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MercadoPagoRepository
     */
    protected $mercadoPagoRepo;

    public function setUp()
    {
        parent::setUp();
        $this->mercadoPagoRepo = App::make(MercadoPagoRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMercadoPago()
    {
        $mercadoPago = $this->fakeMercadoPagoData();
        $createdMercadoPago = $this->mercadoPagoRepo->create($mercadoPago);
        $createdMercadoPago = $createdMercadoPago->toArray();
        $this->assertArrayHasKey('id', $createdMercadoPago);
        $this->assertNotNull($createdMercadoPago['id'], 'Created MercadoPago must have id specified');
        $this->assertNotNull(MercadoPago::find($createdMercadoPago['id']), 'MercadoPago with given id must be in DB');
        $this->assertModelData($mercadoPago, $createdMercadoPago);
    }

    /**
     * @test read
     */
    public function testReadMercadoPago()
    {
        $mercadoPago = $this->makeMercadoPago();
        $dbMercadoPago = $this->mercadoPagoRepo->find($mercadoPago->id);
        $dbMercadoPago = $dbMercadoPago->toArray();
        $this->assertModelData($mercadoPago->toArray(), $dbMercadoPago);
    }

    /**
     * @test update
     */
    public function testUpdateMercadoPago()
    {
        $mercadoPago = $this->makeMercadoPago();
        $fakeMercadoPago = $this->fakeMercadoPagoData();
        $updatedMercadoPago = $this->mercadoPagoRepo->update($fakeMercadoPago, $mercadoPago->id);
        $this->assertModelData($fakeMercadoPago, $updatedMercadoPago->toArray());
        $dbMercadoPago = $this->mercadoPagoRepo->find($mercadoPago->id);
        $this->assertModelData($fakeMercadoPago, $dbMercadoPago->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMercadoPago()
    {
        $mercadoPago = $this->makeMercadoPago();
        $resp = $this->mercadoPagoRepo->delete($mercadoPago->id);
        $this->assertTrue($resp);
        $this->assertNull(MercadoPago::find($mercadoPago->id), 'MercadoPago should not exist in DB');
    }
}
