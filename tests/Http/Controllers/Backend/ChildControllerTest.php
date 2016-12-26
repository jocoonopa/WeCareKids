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

        $this->user = \App\Model\User::find(102);
    }
    
    public function testIndex()
    {
        $this->actingAs($this->user)
            ->visit('/backend/child')
            ->see('受测者列表')
        ;
    }
}