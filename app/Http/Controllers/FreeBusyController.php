<?php

namespace App\Http\Controllers;

use App\Freebusy\Filter;
use App\Freebusy\Meeting;
use App\Models\Employee;
use App\Models\Freebusy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FreeBusyController extends Controller
{
    public function getData()
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

    }

    public function storeData()
    {
        $path = base_path(config('services.freebusy.path')). '/clear_freebusy.json';
        $filtred_items = json_decode(file_get_contents($path));
        $filter = new Filter();
        foreach($filtred_items as $key=>$item) {
            $filter->storeData($item->name, $key, $item->dates);
        }
    }

    public function meeting()
    {
        $employees = Employee::orderBy('name', 'asc')->get();
        return view('requestMeeting', compact('employees'));
    }

    public function requestMeeting(Request $request)
    {
        $data = $request->all();
        $meetingClass = new Meeting();
        $freetimes = $meetingClass->findFreeTime($data);
        $suggested_meeting_date_times = $meetingClass->suggestMeeting($freetimes);
        dd($suggested_meeting_date_times);
    }
}
