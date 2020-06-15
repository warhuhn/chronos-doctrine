<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:42
 */

namespace Tests\Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeType;

class ChronosDateTimeTypeTest extends TestCase
{

	/**
	 * @var AbstractPlatform
	 */
    private $platform;

    /**
     * @var Type
     */
    private $type;

    public static function setUpBeforeClass(): void
    {
        Type::addType('chronos_datetime', ChronosDateTimeType::class);
    }


    protected function setUp(): void
    {
        $this->platform = $this->getPlatformMock();
        $this->type = Type::getType('chronos_datetime');
    }


	public function testInvalidDateConversion(): void
	{
		$this->expectException(ConversionException::class);
		$this->type->convertToPHPValue('aaaa', $this->platform);
    }

    public function testConvertToPhpValue(): void
    {
        $obj = $this->type->convertToPHPValue('2016-11-05 07:54:02', $this->platform);

        static::assertInstanceOf(Chronos::class, $obj);
        static::assertEquals('2016-11-05 07:54:02', $obj->format('Y-m-d H:i:s'));
    }

    public function testConvertToDatabaseValue(): void
    {
        $value = $this->type->convertToDatabaseValue(new Chronos('2016-11-05 07:54:02'), $this->platform);

        static::assertEquals('2016-11-05 07:54:02', $value);
    }

    public function testNull(): void
    {
        $obj = $this->type->convertToPHPValue(null, $this->platform);

        static::assertNull($obj);
    }
    private function getPlatformMock(): MockObject
	{
        return $this->getMockBuilder(AbstractPlatform::class)
            ->getMockForAbstractClass();
    }

}
