<?php

declare(strict_types=1);

namespace App\Base\Enum;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class ArrayEnumAnnotation
{
    /**
     * @Required
     *
     * @var string
     */
    public $class;
}
