<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\WithoutMiddleware;

class AmtAlsRptControllerTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testView()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }
        
        $user = \App\Model\User::find(102);

        $this->actingAs($user)
            ->visit('/backend/amt_als_rpt/1')
            ->see('董小麒一號')
        ;
    }
}
