<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AmtDiagGroupControllerTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEdit()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }

        parent::setUp();

        $user = \App\Model\User::find(102);

        $random = md5(time());

        $this->actingAs($user)
            ->visit('/backend/amt/2/amt_diag_group/34/edit')
            ->see('确认')
            ->type($random, 'content')
            ->press('确认')
            ->see($random)
            ->see('修改完成!')
        ;
    }
}
