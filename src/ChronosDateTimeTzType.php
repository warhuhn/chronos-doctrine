<?php

namespace Warhuhn\Doctrine\DBAL\Types;


use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeTzImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use Warhuhn\Doctrine\DBAL\Types\Traits\ExtendsDoctrineType;

class ChronosDateTimeTzType extends Type
{
    use ExtendsDoctrineType;

    const CHRONOS_DATETIMETZ = 'chronos_datetimetz';

    public function __construct()
    {
        $this->type = new DateTimeTzImmutableType();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::CHRONOS_DATETIMETZ;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Chronos) {
            return $value->format($platform->getDateTimeTzFormatString());
        }

        throw InvalidType::new(
            $value,
            static::class,
            ['null', Chronos::class],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Chronos
    {
        if ($value === null) {
            return null;
        }

        $dateTime = $this->type->convertToPHPValue($value, $platform);

        return Chronos::instance($dateTime);
    }
}
