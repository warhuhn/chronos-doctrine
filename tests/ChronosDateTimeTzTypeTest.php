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
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeTzType;

class ChronosDateTimeTzTypeTest extends TestCase
{

    private $platform;

    /**
     * @var Type
     */
    private $type;

    public static function setUpBeforeClass()
    {
        Type::addType('chronos_datetimetz', ChronosDateTimeTzType::class);
    }


    protected function setUp()
    {
        $this->platform = $this->getPlatformMock();
        $this->type = Type::getType('chronos_datetimetz');
    }


    /**
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testInvalidDateConversion()
    {
        $this->type->convertToPHPValue('aaaa', $this->platform);
    }

    public function testConvertToPhpValue()
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

    public function testConvertToDatabaseValue()
    {
        $this->platform
            ->method('getDateTimeTzFormatString')
            ->willReturn('Y-m-d H:i:sO');

        $value = $this->type->convertToDatabaseValue(new Chronos('2016-11-05 07:54:02+0400'), $this->platform);

        static::assertEquals('2016-11-05 07:54:02+0400', $value);
    }

    private function getPlatformMock()
    {
        return $this->getMockBuilder(AbstractPlatform::class)
            ->setMethods(['getDateTimeTzFormatString'])
            ->getMockForAbstractClass();
    }

}
