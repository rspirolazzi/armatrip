<?php

use Faker\Factory as Faker;
use App\Models\MercadoPago;
use App\Repositories\MercadoPagoRepository;

trait MakeMercadoPagoTrait
{
    /**
     * Create fake instance of MercadoPago and save it in database
     *
     * @param array $mercadoPagoFields
     * @return MercadoPago
     */
    public function makeMercadoPago($mercadoPagoFields = [])
    {
        /** @var MercadoPagoRepository $mercadoPagoRepo */
        $mercadoPagoRepo = App::make(MercadoPagoRepository::class);
        $theme = $this->fakeMercadoPagoData($mercadoPagoFields);
        return $mercadoPagoRepo->create($theme);
    }

    /**
     * Get fake instance of MercadoPago
     *
     * @param array $mercadoPagoFields
     * @return MercadoPago
     */
    public function fakeMercadoPago($mercadoPagoFields = [])
    {
        return new MercadoPago($this->fakeMercadoPagoData($mercadoPagoFields));
    }

    /**
     * Get fake data of MercadoPago
     *
     * @param array $postFields
     * @return array
     */
    public function fakeMercadoPagoData($mercadoPagoFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'address_id' => $fake->randomDigitNotNull,
            'address_flat' => $fake->word,
            'items' => $fake->randomDigitNotNull,
            'payment' => $fake->randomDigitNotNull,
            'comments' => $fake->word,
            'cash' => $fake->word,
            'sub_total' => $fake->word,
            'delivery' => $fake->word,
            'total' => $fake->word,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $mercadoPagoFields);
    }
}
