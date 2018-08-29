<?php

declare(strict_types=1);

namespace App\Enum;

use Consistence\Enum\Enum;

class EQF extends Enum
{
    public const NSP1 = 1; //Základní vzdělání
    public const NSP2 = 2; //Střední vzdělání; Střední vzdělání s výučním listem
    public const NSP3 = 3; //Střední vzdělání s výučním listem
    public const NSP4 = 4; //Střední vzdělání s maturitní zkouškou
    public const NSP5 = 5; //Odborné specializace, pomaturitní vzdělání
    public const NSP6 = 6; //Vyšší odborné vzdělání; Bakalářský studijní program
    public const NSP7 = 7; //Magisterský studijní program
    public const NSP8 = 8; //Doktorský studijní program
}
