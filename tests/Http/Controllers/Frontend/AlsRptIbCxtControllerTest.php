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
        
        $this->visit("/analysis/r/i/channel/{$channel->id}/cxt/auth?public_key={$channel->public_key}")
            ->see('连络电话')
            ->see('优尼尔')
        ;
    }
}
