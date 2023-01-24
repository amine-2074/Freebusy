<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Freebusy\Filter;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path =  base_path(config('services.freebusy.path')). '/freebusy.txt';
        $content = File::get($path);
        $lines = explode("\n", $content);
        $filter = new Filter();
        $data = $filter->filterData($lines);
        $filtred_items = $filter->filterItems($data);
        $clear_data = json_encode($filtred_items);
        $store_path = base_path(config('services.freebusy.path')). '/clear_freebusy.json';
        file_put_contents($store_path, $clear_data);
        $filter->SendData();
    }
}
