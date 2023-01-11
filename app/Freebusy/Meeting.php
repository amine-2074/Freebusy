<?php

namespace App\Freebusy;

use App\Models\Employee;
use App\Models\Freebusy;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Meeting
{
    public function findFreeTime($data)
    {
        $earliest_requested_date = date('Y-m-d H:i:s', strtotime($data['earliest_requested_date']));
        $latest_requested_date = date('Y-m-d H:i:s', strtotime($data['latest_requested_date']));
        $participant_id = $data['participants'][0];
        $busy_dates = Freebusy::where('employee_id' , $participant_id)->get();
        $day_busy_date = [];

        //put all busy dates in the requested period period in one array
        foreach($busy_dates as $busy)
        {
            if(strtotime($busy->start_busy) >= strtotime($earliest_requested_date) && strtotime($busy->end_busy) <= strtotime($latest_requested_date))
            $day_busy_date[] = [
                'start_busy' =>$busy->start_busy->format('Y-m-d H:i:s'),
                'end_busy' =>$busy->end_busy->format('Y-m-d H:i:s')
            ];
        }
        
        dd(collect($day_busy_date)->sortBy('start_busy')->all());
    }
}