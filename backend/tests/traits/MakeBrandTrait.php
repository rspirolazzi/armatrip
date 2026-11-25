<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Admin\Brand;
use App\Repositories\Admin\BrandRepository;

trait MakeBrandTrait
{
    /**
     * Create fake instance of Brand and save it in database
     *
     * @param array $brandFields
     * @return Brand
     */
    public function makeBrand($brandFields = [])
    {
        /** @var BrandRepository $brandRepo */
        $brandRepo = \App::make(BrandRepository::class);
        $theme = $this->fakeBrandData($brandFields);
        return $brandRepo->create($theme);
    }

    /**
     * Get fake instance of Brand
     *
     * @param array $brandFields
     * @return Brand
     */
    public function fakeBrand($brandFields = [])
    {
        return new Brand($this->fakeBrandData($brandFields));
    }

    /**
     * Get fake data of Brand
     *
     * @param array $brandFields
     * @return array
     */
    public function fakeBrandData($brandFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'code' => $fake->word,
            'description' => $fake->word,
            'order' => $fake->randomDigitNotNull,
            'active' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $brandFields);
    }
}
