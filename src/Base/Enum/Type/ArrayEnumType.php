<?php

declare(strict_types=1);

namespace App\Base\Enum\Type;

use App\Base\Enum\Exeption\InvalidInputValueException;
use Consistence\Doctrine\Enum\Type\EnumType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayEnumType extends \Doctrine\DBAL\Types\SimpleArrayType
{
    public const NAME = 'array_enum';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param array                                     $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     *
     * @throws InvalidInputValueException
     *
     * @return array
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!\is_array($value)) {
            throw new InvalidInputValueException(\sprintf('Value parsed with ArrayEnumType must be an array of enum'));
        }

        $output = [];

        foreach ($value as $enum) {
            $output[] = EnumType::convertToDatabaseValue($enum);
        }

        return parent::convertToDatabaseValue($output, $platform);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
