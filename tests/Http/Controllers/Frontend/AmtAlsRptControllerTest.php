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
        $this->visit('/frontend/amt_als_rpt/1')
            ->see('董小麒一號')
        ;
    }
}
