<?php

namespace App\Utility\Services;

use App\Model\AmtDiagStandard;
use App\Utility\Services\AmtCell\MoveTrait;
use App\Utility\Services\AmtCell\ParserTrait;

class AmtCellService
{
    use ParserTrait, MoveTrait;

    public static $whiteAlphabets = ['a', 'n', 'd', 'o', 'r'];
    public static $whiteChars = ['(', ')'];
    protected $str;

    
}