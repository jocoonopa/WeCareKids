<?php

namespace Tests\Http\Controllers\Frontend;

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
        
        $this->visit('/frontend/amt_als_rpt/1')
            ->see('董小麒一號')
        ;
    }
}
