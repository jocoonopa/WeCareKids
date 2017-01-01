<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AmtDiagControllerTest extends \Tests\TestCase
{
    use DatabaseTransactions;
    
    public function testApplication()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }
        
        $user = \App\Model\User::find(102);

        $this->actingAs($user)
            ->visit('/backend/amt_diag_group/35/amt_diag')
            ->see('大题列表')
        ;
    }
}