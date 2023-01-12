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
        $busy_dates = Freebusy::where('employee_id', $participant_id)->get();
        $day_busy_date = [];

        //put all busy dates in the requested period period in one array
        foreach ($busy_dates as $busy) {
            if (strtotime($busy->start_busy) >= strtotime($earliest_requested_date) && strtotime($busy->end_busy) <= strtotime($latest_requested_date)) {
                $day_busy_date[] = [
                    'start_busy' =>$busy->start_busy->format('Y-m-d H:i:s'),
                    'end_busy' =>$busy->end_busy->format('Y-m-d H:i:s')
                ];
            }
        }
        // dd(collect($day_busy_date)->sortBy('start_busy'));

        $collection = collect($day_busy_date)->sortBy('start_busy');
        // dd($collection);
        $busyByDay = [];
        foreach ($collection as $busy) {
            $date = date("Y-m-d", strtotime($busy['start_busy']));
            $filteredDay = $collection->filter(function ($item) use ($date) {
                return date("Y-m-d", strtotime($item['start_busy'])) == $date;
            });
            foreach ($filteredDay as $key=>$datetime) {
                $day = date('Y-m-d', strtotime($filteredDay[$key]['start_busy']));
                $busyByDay[$day][] = $datetime;
            }
        }
        foreach ($busyByDay as $day=>$value) {
            // dd($value);
            $result = array_reduce($busyByDay[$day], function ($carry, $item) {
                $key = $item['start_busy'].$item['end_busy'];
                if (!isset($carry[$key])) {
                    $carry[$key] = $item;
                }
                return $carry;
            }, array());
            // dd($busyByDay[$day]);
            $busyByDay[$day] = array_values($result);
        }
        $freetime = [];
        $free_datetime = [];

        foreach ($busyByDay as $date => $intervals) {
            $date_start = Carbon::parse($date)->startOfDay()->addHours($data['office_hours_start'])->format('Y-m-d H:i:s');
            $date_end = Carbon::parse($date)->startOfDay()->addHours($data['office_hours_end'])->format('Y-m-d H:i:s');
            // Initialize an array to store the free intervals for the current date
            $busy = [];
            // Check if the first busy interval starts before the office start time
            if (strtotime($intervals[0]['start_busy']) <= strtotime($date_start)) {
                $busy[] = [$date_start, $intervals[0]['start_busy']];
            }
            // Iterate through the busy intervals for the current date
            for ($i = 0; $i < count($intervals) - 1; $i++) {
                // Add the free interval between the current busy interval and the next busy interval
                $busy[] = [$intervals[$i]['end_busy'], $intervals[$i + 1]['start_busy']];
            }
            // Check if the last busy interval ends after the office end time
            if (!strtotime($intervals[count($intervals) - 1]['end_busy']) >= strtotime($date_end)) {
                $busy[] = [$intervals[count($intervals) - 1]['end_busy'], $date_end];
            }
            // Add the free intervals for the current date to the free datetime array
            $free_datetime[$date] = $busy;
        }

        dd($free_datetime);
    }
    public function getFreeIntervals($start, $end, $busy)
    {
        sort($busy);
        $free = [[$start, $busy[0][0]]];
        for ($i = 0; $i < count($busy) - 1; $i++) {
            $free[] = [$busy[$i][1], $busy[$i + 1][0]];
        }
        $free[] = [$busy[count($busy) - 1][1], $end];
        return $free;
    }
}
