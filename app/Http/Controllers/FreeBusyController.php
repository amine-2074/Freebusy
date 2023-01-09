<?php

namespace App\Http\Controllers;

use App\Freebusy\Filter;
use App\Models\Employee;
use Illuminate\Http\Request;
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

    public function meeting()
    {
        $employees = Employee::orderBy('name', 'asc')->get();
        return view('requestMeeting', compact('employees'));
    }

    public function requestMeeting(Request $request)
    {
        dd($request->all());
    }
}
