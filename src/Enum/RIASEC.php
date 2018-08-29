<?php

declare(strict_types=1);

namespace App\Enum;

use Consistence\Enum\Enum;

class RIASEC extends Enum
{
    public const R = 'Prakticko-technický (R)';
    public const I = 'Intelektuálně-výzkumný (I)';
    public const A = 'Umělecko-jazykový (A)';
    public const S = 'Sociální (S)';
    public const E = 'Podnikatelský (E)';
    public const C = 'Administrativně-správní (C)';
}
