<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

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
        //
    }
}
