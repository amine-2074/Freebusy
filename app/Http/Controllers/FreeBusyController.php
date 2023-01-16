<?php

namespace App\Http\Controllers;

use App\Freebusy\Filter;
use App\Freebusy\Meeting;
use App\Models\Employee;
use App\Models\Freebusy;
use App\Models\Meeting as ModelsMeeting;
use Carbon\Carbon;
use DateTime;
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
        $participants_ids = $data['participants'];
        $length = $data['meeting_length'];
        $participants = [];
        foreach($participants_ids as $participants_id)
        {
            $participant = Employee::where('id', $participants_id)->first();
            $participants[] = $participant->name;
        }
        $meetingClass = new Meeting();
        $freetimes = $meetingClass->findFreeTime($data);
        $suggested_meeting_date_times = $meetingClass->suggestMeeting($freetimes, $data['meeting_length']);
        return view('bookMeeting', compact('suggested_meeting_date_times', 'participants', 'length'));
    }

    public function bookMeeting($date, $participants, $length)
    {
        $startMeeting = new DateTime($date);
        $date = new DateTime($date);
        $endmeeting = $date->modify('+'.$length.' minutes');
        //store meeting
        $meeting = new ModelsMeeting();
        $meeting->participants = $participants;
        $meeting->start = $startMeeting;
        $meeting->end = $startMeeting;
        $meeting->save();

        //add meeting datetime to freebusy table
        $participants_decoded = json_decode($participants);
        foreach($participants_decoded as $participant)
        {
            $employee = Employee::where('name', $participant)->first();
            $newbusydate = new Freebusy();
            $newbusydate->start_busy = $startMeeting;
            $newbusydate->end_busy =$endmeeting;
            $newbusydate->employee_id = $employee->id;
            $newbusydate->save();
        }

        $startMeeting = $startMeeting->format('Y-m-d H:i:s');
        $endmeeting = $endmeeting->format('Y-m-d H:i:s');
        return view('showMeetingDetails', compact('startMeeting', 'participants_decoded', 'endmeeting'));
    }
}
