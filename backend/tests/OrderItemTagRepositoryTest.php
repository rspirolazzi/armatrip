<?php

use App\Models\OrderItemFlavor;
use App\Repositories\OrderItemFlavorRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderItemTagRepositoryTest extends TestCase
{
    use MakeOrderItemTagTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var OrderItemFlavorRepository
     */
    protected $orderItemTagRepo;

    public function setUp()
    {
        parent::setUp();
        $this->orderItemTagRepo = App::make(OrderItemFlavorRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateOrderItemTag()
    {
        $orderItemTag = $this->fakeOrderItemTagData();
        $createdOrderItemTag = $this->orderItemTagRepo->create($orderItemTag);
        $createdOrderItemTag = $createdOrderItemTag->toArray();
        $this->assertArrayHasKey('id', $createdOrderItemTag);
        $this->assertNotNull($createdOrderItemTag['id'], 'Created OrderItemFlavor must have id specified');
        $this->assertNotNull(OrderItemFlavor::find($createdOrderItemTag['id']), 'OrderItemFlavor with given id must be in DB');
        $this->assertModelData($orderItemTag, $createdOrderItemTag);
    }

    /**
     * @test read
     */
    public function testReadOrderItemTag()
    {
        $orderItemTag = $this->makeOrderItemTag();
        $dbOrderItemTag = $this->orderItemTagRepo->find($orderItemTag->id);
        $dbOrderItemTag = $dbOrderItemTag->toArray();
        $this->assertModelData($orderItemTag->toArray(), $dbOrderItemTag);
    }

    /**
     * @test update
     */
    public function testUpdateOrderItemTag()
    {
        $orderItemTag = $this->makeOrderItemTag();
        $fakeOrderItemTag = $this->fakeOrderItemTagData();
        $updatedOrderItemTag = $this->orderItemTagRepo->update($fakeOrderItemTag, $orderItemTag->id);
        $this->assertModelData($fakeOrderItemTag, $updatedOrderItemTag->toArray());
        $dbOrderItemTag = $this->orderItemTagRepo->find($orderItemTag->id);
        $this->assertModelData($fakeOrderItemTag, $dbOrderItemTag->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteOrderItemTag()
    {
        $orderItemTag = $this->makeOrderItemTag();
        $resp = $this->orderItemTagRepo->delete($orderItemTag->id);
        $this->assertTrue($resp);
        $this->assertNull(OrderItemFlavor::find($orderItemTag->id), 'OrderItemFlavor should not exist in DB');
    }
}
