<?php

namespace Warhuhn\Doctrine\DBAL\Types\Traits;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Shares often used methods
 */
trait ExtendsDoctrineType
{
    protected Type $type;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->type->getSQLDeclaration($column, $platform);
    }
}