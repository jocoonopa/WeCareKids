<?php

namespace Tests\Http\Controllers\Backend;

class AlsRptIbChannelControllerTest extends \Tests\TestCase
{    
    public function testApplication()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }
        
        parent::setUp();

        $user = \App\Model\User::find(102);

        $this->actingAs($user)
            ->visit('/')
            ->see('WELCOME 優尼爾')
            ->see($user->name)
        ;
    }
}
