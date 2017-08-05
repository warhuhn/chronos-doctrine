<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:27
 */

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
    public function getName()
    {
        return self::CHRONOS_DATETIME;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $dateTime = parent::convertToPHPValue($value, $platform);

        return Chronos::instance($dateTime);
    }
}