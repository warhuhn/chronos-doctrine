<?php

namespace Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\ChronosDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use Warhuhn\Doctrine\DBAL\Types\Traits\ExtendsDoctrineType;

class ChronosDateType extends Type
{
    use ExtendsDoctrineType;

    const CHRONOS_DATE = 'chronos_date';

    public function __construct()
    {
        $this->type = new DateImmutableType();
    }

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

        throw InvalidType::new(
            $value,
            static::class,
            ['null', ChronosDate::class],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ChronosDate
    {
        if ($value === null) {
            return null;
        }

        $dateTime = $this->type->convertToPHPValue($value, $platform);

        try {
            return new ChronosDate($dateTime);
        } catch (\Exception $e) {
            throw InvalidFormat::new(
                $value,
                static::class,
                $platform->getDateFormatString(),
            );
        }
    }
}
