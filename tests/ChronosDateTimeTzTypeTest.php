<?php

namespace Tests\Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\MariaDB1010Platform;
use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Tests\Warhuhn\Doctrine\DBAL\Types\Traits\HasPlatformDataProvider;
use Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeTzType;

class ChronosDateTimeTzTypeTest extends TestCase
{
    /**
     * @var Type
     */
    private $type;

    public static function setUpBeforeClass(): void
    {
        Type::addType('chronos_datetimetz', ChronosDateTimeTzType::class);
    }


    protected function setUp(): void
    {
        $this->type = Type::getType('chronos_datetimetz');
    }

    /**
     * @dataProvider providePlatform
     */
	public function testInvalidDateConversion(AbstractPlatform $platform): void
    {
		$this->expectException(ConversionException::class);
		$this->type->convertToPHPValue('aaaa', $platform);
    }

    /**
     * @dataProvider providePlatform
     */
    public function testConvertToPhpValue(AbstractPlatform $platform, $time, $dbTime): void
    {
        // Some platforms don't have a tz format string that actually saves the timezone, and I am too lazy to implement different tests for that.
        if ($platform->getDateTimeFormatString() === $platform->getDateTimeTzFormatString()) {
            static::markTestSkipped(sprintf('DBAL Platform %s does not support datetime with timezones', basename(get_class($platform))));
        }

        /** @var Chronos $obj */
        $obj = $this->type->convertToPHPValue($dbTime, $platform);

        static::assertInstanceOf(Chronos::class, $obj);

        static::assertEquals($time, $obj->format('Y-m-d H:i:sO'));
        static::assertEquals(14400, $obj->getOffset());
    }

    /**
     * @dataProvider providePlatform
     */
    public function testConvertToDatabaseValue(AbstractPlatform $platform, $time, $dbTime): void
    {
        // Some platforms don't have a tz format string that actually saves the timezone, and I am too lazy to implement different tests for that.
        if ($platform->getDateTimeFormatString() === $platform->getDateTimeTzFormatString()) {
            static::markTestSkipped(sprintf('DBAL Platform %s does not support datetime with timezones', basename(get_class($platform))));
        }

        $value = $this->type->convertToDatabaseValue(new Chronos($time), $platform);

        static::assertEquals($dbTime, $value);
    }

    /**
     * @dataProvider providePlatform
     */
    public function testNull(AbstractPlatform $platform): void
    {
        // Some platforms don't have a tz format string that actually saves the timezone, and I am too lazy to implement different tests for that.
        if ($platform->getDateTimeFormatString() === $platform->getDateTimeTzFormatString()) {
            static::markTestSkipped(sprintf('DBAL Platform %s does not support datetime with timezones', basename(get_class($platform))));
        }

        $obj = $this->type->convertToPHPValue(null, $platform);

        static::assertNull($obj);
    }

    public function providePlatform(): array
    {
        return [
            'db2'         => [new DB2Platform,         '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02',              ],
            'mariadb1010' => [new MariaDB1010Platform, '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02',              ],
            'mariadb'     => [new MariaDBPlatform,     '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02',              ],
            'mysql'       => [new MySQLPlatform,       '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02',              ],
            'oracle'      => [new OraclePlatform,      '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02+04:00',        ],
            'pgsql'       => [new PostgreSQLPlatform,  '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02+0400',         ],
            'sqlite'      => [new SQLitePlatform,      '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02',              ],
            'sqlsrv'      => [new SQLServerPlatform,   '2016-11-05 07:54:02+0400', '2016-11-05 07:54:02.000000 +04:00',],
        ];
    }
}
