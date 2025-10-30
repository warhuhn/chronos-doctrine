<?php

namespace Tests\Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\ChronosDate;
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
use Warhuhn\Doctrine\DBAL\Types\ChronosDateType;

class ChronosDateTypeTest extends TestCase
{
    /**
     * @var Type
     */
    private $type;

    public static function setUpBeforeClass(): void
    {
        Type::addType('chronos_date', ChronosDateType::class);
    }


    protected function setUp(): void
    {
        $this->type = Type::getType('chronos_date');
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
    public function testConvertToPhpValue(AbstractPlatform $platform, $date, $dbDate): void
    {
        $obj = $this->type->convertToPHPValue($dbDate, $platform);

        static::assertInstanceOf(ChronosDate::class, $obj);
        static::assertEquals($date, $obj->format('Y-m-d'));
    }

    /**
     * @dataProvider providePlatform
     */
    public function testConvertToDatabaseValue(AbstractPlatform $platform, $date, $dbDate): void
    {
        $value = $this->type->convertToDatabaseValue(new ChronosDate($date), $platform);

        static::assertEquals($dbDate, $value);
    }

    /**
     * @dataProvider providePlatform
     */
    public function testNull(AbstractPlatform $platform): void
    {
        $obj = $this->type->convertToPHPValue(null, $platform);

        static::assertNull($obj);
    }

    public function providePlatform(): array
    {
        return [
            'db2'         => [new DB2Platform, '2016-11-05', '2016-11-05'],
            'mariadb1010' => [new MariaDB1010Platform, '2016-11-05', '2016-11-05'],
            'mariadb'     => [new MariaDBPlatform, '2016-11-05', '2016-11-05'],
            'mysql'       => [new MySQLPlatform, '2016-11-05', '2016-11-05'],
            'oracle'      => [new OraclePlatform, '2016-11-05', '2016-11-05 00:00:00'],
            'pgsql'       => [new PostgreSQLPlatform, '2016-11-05', '2016-11-05'],
            'sqlite'      => [new SQLitePlatform, '2016-11-05', '2016-11-05'],
            'sqlsrv'      => [new SQLServerPlatform, '2016-11-05', '2016-11-05'],
        ];
    }
}
