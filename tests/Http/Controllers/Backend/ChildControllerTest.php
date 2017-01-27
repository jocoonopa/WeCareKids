<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChildControllerTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }

        $this->user = \App\Model\User::find(102);
    }
    
    public function testIndex()
    {
        $this->actingAs($this->user)
            ->visit('/backend/child')
            ->see('孩童列表')
        ;
    }
}