<?php

use Illuminate\Database\Seeder;
use App\Good;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Meizu M5 Note 32GB Grey', 'Apple iPhone X 64GB Space Gray', 'Apple iPhone SE 64GB Rose Gold',
            'Телевизор Sony KDL-32WE613', 'Телевизор Samsung UE32J4710AKXUA', 'Телевизор Philips 32PFS4132',
            'Холодильник SAMSUNG RB29FSRNDSA/UA', 'Холодильник BOSCH KGN39VI35', 'Холодильник ELENBERG MRF 146-O',
            'Стиральная машина узкая ELECTROLUX EWS 1266 CI', 'Стиральная машина узкая ZANUSSI ZWSO 680 V'
        ];
        foreach(range(0,count($names)-1) as $i) {
            Good::create([
                'name' => $names[$i],
                'price' => mt_rand(115,365)*100,
                'number' => mt_rand(1,5),
                'category_id' => ($i < 3) ? 1 : 2,
                'description' => $names[$i] . ' - description.',
            ]);
        }
    }
}
