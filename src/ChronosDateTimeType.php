<?php

namespace Warhuhn\Doctrine\DBAL\Types;


use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class ChronosDateTimeType extends DateTimeType
{
    const CHRONOS_DATETIME = 'chronos_datetime';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::CHRONOS_DATETIME;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Chronos
    {
        if ($value === null) {
            return null;
        }

        $dateTime = parent::convertToPHPValue($value, $platform);

        return Chronos::instance($dateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
