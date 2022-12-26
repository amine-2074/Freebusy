<?php

namespace App\Http\Controllers;

use App\Freebusy\Filter;
use Illuminate\Support\Facades\File;

class FreeBusyController extends Controller
{
    public function getData()
    {
        $path =  base_path(config('services.freebusy.path')). '/' .'freebusy.txt';
        $content = File::get($path);
        $lines = explode("\n", $content);
        $filter = new Filter();
        $data = $filter->filterData($lines);
        $filtred_items = $filter->filterItems($data);
        foreach($filtred_items as $key=>$item) {
            $filter->storeData($item['name'], $key, $item['dates']);
        }
    }
}
