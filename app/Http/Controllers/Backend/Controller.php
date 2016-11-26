<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller AS DefaultController;

class Controller extends DefaultController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
