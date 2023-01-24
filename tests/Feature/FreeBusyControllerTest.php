<?php

namespace Tests\Feature;

use App\Models\Employee;
use Tests\TestCase;

class FreeBusyControllerTest extends TestCase
{

    public function testRequestMeetingSuccessfully()
    {
        $earliest_requested_date = '2015-05-01T12:00';
        $latest_requested_date = '2015-05-03T14:00';
        $meeting_length = 60;
        $office_hours_start = 8;
        $office_hours_end = 17;
        // Send request to the controller method
        $employee = Employee::where('name', 'Abigail Bell')->first();
        $response = $this->post('/meeting/request', [
            'earliest_requested_date' => $earliest_requested_date,
            'latest_requested_date' => $latest_requested_date,
            'participants' => [$employee->id],
            'meeting_length' => $meeting_length,
            'office_hours_start' => $office_hours_start,
            'office_hours_end' => $office_hours_end,
        ]);
        $response->assertStatus(200);
    }

    public function testbookMeetingSuccessfully()
    {
        $employee = Employee::where('name', 'Abigail Bell')->first();
        $response = $this->get('meeting/booking/2015-05-01 08:00:00/["Abigail Bell","Randall Cassady"]/60');
        $response->assertStatus(200);
    }
}
