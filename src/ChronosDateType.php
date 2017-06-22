<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:39
 */

namespace Warhuhn\Doctrine\DBAL\Types;


use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateType;

class ChronosDateType extends DateType
{
    const CHRONOS_DATE = 'chronos_date';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::CHRONOS_DATE;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $dateTime = parent::convertToPHPValue($value, $platform);

        return Date::instance($dateTime);
    }
}