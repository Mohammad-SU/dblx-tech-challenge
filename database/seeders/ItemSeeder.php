<?php

namespace Database\Seeders;

use App\Models\Download;
use App\Models\Info;
use App\Models\Item;
use App\Models\Weblink;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the item seeds.
     */
    public function run(): void
    {
        Item::factory(4)->for(Info::factory(), 'content')->create();
        Item::factory(2)->for(Weblink::factory(), 'content')->create();
        Item::factory(3)->for(Download::factory(), 'content')->create();
        Item::factory(1)->for(Info::factory(), 'content')->create();
    }
}