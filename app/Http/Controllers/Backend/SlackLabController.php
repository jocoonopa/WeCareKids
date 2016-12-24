<?php

namespace App\Http\Controllers\Backend;

use Slack;

class SlackLabController extends Controller
{
    public function sendForTest()
    {
        Slack::sayHello();
    }
}
