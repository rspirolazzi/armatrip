<?php

use Faker\Factory as Faker;
use App\Models\OrderItemFlavor;
use App\Repositories\OrderItemFlavorRepository;

trait MakeOrderItemTagTrait
{
    /**
     * Create fake instance of OrderItemFlavor and save it in database
     *
     * @param array $orderItemTagFields
     * @return OrderItemFlavor
     */
    public function makeOrderItemTag($orderItemTagFields = [])
    {
        /** @var OrderItemFlavorRepository $orderItemTagRepo */
        $orderItemTagRepo = App::make(OrderItemFlavorRepository::class);
        $theme = $this->fakeOrderItemTagData($orderItemTagFields);
        return $orderItemTagRepo->create($theme);
    }

    /**
     * Get fake instance of OrderItemFlavor
     *
     * @param array $orderItemTagFields
     * @return OrderItemFlavor
     */
    public function fakeOrderItemTag($orderItemTagFields = [])
    {
        return new OrderItemFlavor($this->fakeOrderItemTagData($orderItemTagFields));
    }

    /**
     * Get fake data of OrderItemFlavor
     *
     * @param array $postFields
     * @return array
     */
    public function fakeOrderItemTagData($orderItemTagFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'flavor_id' => $fake->randomDigitNotNull,
            'order_item_id' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $orderItemTagFields);
    }
}
