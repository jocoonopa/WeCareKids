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

        $channel = \App\Model\AlsRptIbChannel::latest()->first();
        
        $this->visit("/analysis/r/i/channel/{$channel->id}/cxt/login")
            ->see('电话号码')
            ->see("/analysis/r/i/channel/{$channel->id}/cxt")
            ->type('0939160705', 'phone')
            ->press('确定')
            ->seePageIs("/analysis/r/i/channel/{$channel->id}/cxt")
            ->see('優尼爾')
        ;
    }
}
