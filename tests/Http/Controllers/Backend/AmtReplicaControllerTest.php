<?php

namespace Tests\Http\Controllers\Backend;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AmtReplicaControllerTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp()
    {
        if ('travis' === env('APP_ENV')) {
            $this->markTestSkipped('travis skipped database integrate tests');
        }
        
        parent::setUp();

        $this->user = \App\Model\User::find(102);
    }
    
    public function testAmt()
    {
        $this->actingAs($this->user)
            ->visit('/backend/amt')
            ->see('评测管理')
        ;
    }

    public function testMap()
    {
        $this->actingAs($this->user)
            ->visit('/backend/amt/2/map')
            ->see('老师手拿一个圈圈')
        ;
    }

    public function testReplica()
    {
        $this->actingAs($this->user)
            ->visit('/backend/amt_replica')
            ->see('评测列表')
        ;
    }
}