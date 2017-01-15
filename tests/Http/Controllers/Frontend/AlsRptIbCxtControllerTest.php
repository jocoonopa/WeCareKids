<?php

namespace Tests\Http\Controllers\Frontend;

class AlsRptIbCxtControllerTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testLoginPage()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }
        
        $this->visit('/analysis/r/i/channel/152/cxt/login')
            ->see('电话号码')
            ->see('/analysis/r/i/channel/152/cxt')
            ->type('0939160705', 'phone')
            ->press('确定')
            ->seePageIs('/analysis/r/i/channel/152/cxt')
            ->see('優尼爾')
        ;
    }
}
