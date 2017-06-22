<?php
/**
 * Created by PhpStorm.
 * User: suigintou
 * Date: 22.06.17
 * Time: 19:42
 */

namespace Tests\Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Warhuhn\Doctrine\DBAL\Types\ChronosDateType;

class ChronosDateTypeTest extends TestCase
{

    private $platform;

    /**
     * @var Type
     */
    private $type;

    public static function setUpBeforeClass()
    {
        Type::addType('chronos_date', ChronosDateType::class);
    }


    protected function setUp()
    {
        $this->platform = $this->getPlatformMock();
        $this->type = Type::getType('chronos_date');
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
        $obj = $this->type->convertToPHPValue('2016-11-05', $this->platform);

        static::assertInstanceOf(Date::class, $obj);
        static::assertEquals('2016-11-05', $obj->format('Y-m-d'));
    }

    public function testConvertToDatabaseValue()
    {
        $value = $this->type->convertToDatabaseValue(new Date('2016-11-05'), $this->platform);

        static::assertEquals('2016-11-05', $value);
    }

    private function getPlatformMock()
    {
        return $this->getMockBuilder(AbstractPlatform::class)
            ->getMockForAbstractClass();
    }

}
