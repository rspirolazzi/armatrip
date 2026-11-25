<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Admin\PackageGroup;
use App\Repositories\Admin\PackageGroupRepository;

trait MakePackageGroupTrait
{
    /**
     * Create fake instance of PackageGroup and save it in database
     *
     * @param array $packageGroupFields
     * @return PackageGroup
     */
    public function makePackageGroup($packageGroupFields = [])
    {
        /** @var PackageGroupRepository $packageGroupRepo */
        $packageGroupRepo = \App::make(PackageGroupRepository::class);
        $theme = $this->fakePackageGroupData($packageGroupFields);
        return $packageGroupRepo->create($theme);
    }

    /**
     * Get fake instance of PackageGroup
     *
     * @param array $packageGroupFields
     * @return PackageGroup
     */
    public function fakePackageGroup($packageGroupFields = [])
    {
        return new PackageGroup($this->fakePackageGroupData($packageGroupFields));
    }

    /**
     * Get fake data of PackageGroup
     *
     * @param array $packageGroupFields
     * @return array
     */
    public function fakePackageGroupData($packageGroupFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'code' => $fake->word,
            'active' => $fake->word,
            'description' => $fake->text,
            'bg_color' => $fake->word,
            'text_color' => $fake->word,
            'styles' => $fake->text,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $packageGroupFields);
    }
}
