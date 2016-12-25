<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlsRptIbChannelControllerTest extends \Tests\TestCase
{
    use DatabaseTransactions;
    
    public function testApplication()
    {
        $user = factory(\App\Model\User::class)->create();

        $this->actingAs($user)
            ->visit('/')
            ->see('Welcome,')
            ->see($user->name)
            ->see('您所建立的评量频道')
        ;
    }
}
