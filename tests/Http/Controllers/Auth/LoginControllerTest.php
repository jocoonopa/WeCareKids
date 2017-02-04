<?php

namespace Tests\Http\Controllers\Auth;

class LoginControllerTest extends \Tests\TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testLoginPage()
    {
        $this->visit('/auth/login')->see(trans('wck.company_name'));
    }

    public function testDatabase()
    {
        $this->seeInDatabase('users', [
            'email' => 'wck_admin@mail.com'
        ]);
    }
}
