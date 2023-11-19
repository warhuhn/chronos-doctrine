<?php

namespace Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\ChronosDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;

class ChronosDateType extends DateType
{
    const CHRONOS_DATE = 'chronos_date';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::CHRONOS_DATE;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ChronosDate) {
            return $value->format($platform->getDateFormatString());
        }

        throw ConversionException::conversionFailedFormat($value, static::class, implode(', ', ['null', ChronosDate::class]));
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ChronosDate
    {
        if ($value === null) {
            return null;
        }

        $dateTime = parent::convertToPHPValue($value, $platform);

        return new ChronosDate($dateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
