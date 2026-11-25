<?php

use Faker\Factory as Faker;
use App\Models\Family;
use App\Repositories\FamilyRepository;

trait MakeFamilyTrait
{
    /**
     * Create fake instance of Family and save it in database
     *
     * @param array $familyFields
     * @return Family
     */
    public function makeFamily($familyFields = [])
    {
        /** @var FamilyRepository $familyRepo */
        $familyRepo = App::make(FamilyRepository::class);
        $theme = $this->fakeFamilyData($familyFields);
        return $familyRepo->create($theme);
    }

    /**
     * Get fake instance of Family
     *
     * @param array $familyFields
     * @return Family
     */
    public function fakeFamily($familyFields = [])
    {
        return new Family($this->fakeFamilyData($familyFields));
    }

    /**
     * Get fake data of Family
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFamilyData($familyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $familyFields);
    }
}
