<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:37
 */

namespace Warhuhn\Doctrine\DBAL\Types;


use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeTzType;

class ChronosDateTimeTzType extends DateTimeTzType
{
    const CHRONOS_DATETIMETZ = 'chronos_datetimetz';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::CHRONOS_DATETIMETZ;
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