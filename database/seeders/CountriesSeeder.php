<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Country::exists()) {
            $this->command->info('Countries table already seeded. Skipping.');
        } else {
            $countries = file_get_contents(resource_path('files/countries/countries.json'));
            $countries = json_decode($countries, true)['countries'];
            Country::insert($countries);
            $this->command->info('Countries seeded.');
        }

        if (State::exists()) {
            $this->command->info('States table already seeded. Skipping.');
        } else {
            $states = file_get_contents(resource_path('files/countries/states.json'));
            $states = json_decode($states, true)['states'];
            State::insert($states);
            $this->command->info('States seeded.');
        }

        if (City::exists()) {
            $this->command->info('Cities table already seeded. Skipping.');
        } else {
            $cities = file_get_contents(resource_path('files/countries/cities.json'));
            $cities = json_decode($cities, true)['cities'];
            collect($cities)
                ->chunk(1000)
                ->each(function ($chunk) {
                    City::insert($chunk->toArray());
                });
            $this->command->info('Cities seeded.');
        }
    }
}
