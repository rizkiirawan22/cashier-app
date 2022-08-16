<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'item_name' => 'Sabun Batang',
                'unit_price' => 3000,
            ],
            [
                'item_name' => 'Mie Instan',
                'unit_price' => 2000,
            ],
            [
                'item_name' => 'Pensil',
                'unit_price' => 1000,
            ],
            [
                'item_name' => 'Kopi Sachet',
                'unit_price' => 1500,
            ],
            [
                'item_name' => 'Air Minum Galon',
                'unit_price' => 20000,
            ],
        ];
        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
