<?php

declare(strict_types=1);

namespace App\Enum;

use Consistence\Enum\Enum;

class WorkUnitType extends Enum
{
    public const TYPE_PROFESSION = 1; //Povolání
    public const TYPE_POSITION = 2; //Typová pozice
}
