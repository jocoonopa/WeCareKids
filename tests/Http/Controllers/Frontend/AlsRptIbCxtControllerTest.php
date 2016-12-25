<?php

namespace Tests\Http\Controllers\Frontend;

use Illuminate\Foundation\Testing\WithoutMiddleware;

class AlsRptIbCxtControllerTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testLoginPage()
    {
        $this->visit('/analysis/r/i/channel/1/cxt/login')
            ->see('电话号码')
            ->see('/analysis/r/i/channel/1/cxt')
            ->type('0939160705', 'phone')
            ->press('确定')
            ->seePageIs('/analysis/r/i/channel/1/cxt')
            ->see('優尼爾')
        ;
    }
}
