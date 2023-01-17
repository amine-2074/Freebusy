<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use Tests\TestCase;

class FreeBusyControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Facade::setFacadeApplication(new \Illuminate\Foundation\Application());
    }

    public function testRequestMeeting()
    {
        $response = $this
            ->postJson(route('meeting.request'), [
                'earliest_requested_date' => "2023-01-17T12:00",
                'earliest_requested_date' => "2023-01-18T10:30",
                'Participants' => [
                0 => "3ce78edf-ddf5-4080-94b6-d2725d411348",
                1 => "f9faac93-788b-4754-9d5a-597f452e266d"
                ],
                'meeting_length' => 60,
                'office_hours_start' => 9,
                'office_hours_end' => 15
            ]);
        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }
}
