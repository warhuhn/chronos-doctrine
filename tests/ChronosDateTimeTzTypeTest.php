<?php

namespace Tests\Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeTzType;

class ChronosDateTimeTzTypeTest extends TestCase
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
        Type::addType('chronos_datetimetz', ChronosDateTimeTzType::class);
    }


    protected function setUp(): void
    {
        $this->platform = $this->getPlatformMock();
        $this->type = Type::getType('chronos_datetimetz');
    }


	public function testInvalidDateConversion(): void
    {
        $this->platform
            ->method('getDateTimeTzFormatString')
            ->willReturn('Y-m-d H:i:sO');

		$this->expectException(ConversionException::class);
		$this->type->convertToPHPValue('aaaa', $this->platform);
    }

    public function testConvertToPhpValue(): void
    {
        $this->platform
            ->method('getDateTimeTzFormatString')
            ->willReturn('Y-m-d H:i:sO');

        /** @var Chronos $obj */
        $obj = $this->type->convertToPHPValue('2016-11-05 07:54:02+0400', $this->platform);

        static::assertInstanceOf(Chronos::class, $obj);
        static::assertEquals('2016-11-05 07:54:02+0400', $obj->format('Y-m-d H:i:sO'));
        static::assertEquals(14400, $obj->getOffset());
    }

    public function testConvertToDatabaseValue(): void
    {
        $this->platform
            ->method('getDateTimeTzFormatString')
            ->willReturn('Y-m-d H:i:sO');

        $value = $this->type->convertToDatabaseValue(new Chronos('2016-11-05 07:54:02+0400'), $this->platform);

        static::assertEquals('2016-11-05 07:54:02+0400', $value);
    }

    public function testNull(): void
    {
        $obj = $this->type->convertToPHPValue(null, $this->platform);

        static::assertNull($obj);
    }

    private function getPlatformMock(): MockObject
    {
        return $this->getMockBuilder(AbstractPlatform::class)
            ->onlyMethods(['getDateTimeTzFormatString'])
            ->getMockForAbstractClass();
    }

}
