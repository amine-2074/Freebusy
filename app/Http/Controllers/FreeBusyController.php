<?php

namespace App\Http\Controllers;

use App\Freebusy\Meeting;
use App\Models\Employee;
use App\Models\Freebusy;
use App\Models\Meeting as ModelsMeeting;
use DateTime;
use Illuminate\Http\Request;

class FreeBusyController extends Controller
{
    public function meeting()
    {
        $employees = Employee::orderBy('name', 'asc')->get();
        return view('requestMeeting', compact('employees'));
    }

    public function requestMeeting(Request $request)
    {
        $validator = $request->validate([
            'earliest_requested_date' => 'required|date',
            'latest_requested_date' => 'required|date|after:earliest_requested_date',
            'participants' => 'filled|array',
            'meeting_length' => 'required',
            'office_hours_start' => 'required',
            'office_hours_end' => 'required',
        ]);

        $data = $request->all();
        $participants_ids = $data['participants'];
        $length = $data['meeting_length'];
        $participants = [];
        foreach ($participants_ids as $participants_id) {
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
        foreach ($participants_decoded as $participant) {
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
