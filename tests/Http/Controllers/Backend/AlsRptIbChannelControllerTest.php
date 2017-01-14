<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlsRptIbChannelControllerTest extends \Tests\TestCase
{    
    public function testApplication()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }
        
        $user = \App\Model\User::find(102);

        $this->actingAs($user)
            ->visit('/')
            ->see('Welcome,')
            ->see($user->name)
            ->see('问卷列表')
        ;
    }
}
