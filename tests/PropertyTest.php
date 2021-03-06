<?php

namespace Neat\Object\Test;

use DateTime;
use Neat\Object\Property;
use Neat\Object\Test\Helper\User;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class PropertyTest extends TestCase
{
    /** @noinspection PhpDocMissingThrowsInspection */
    /**
     * Create property
     *
     * @param string $name
     * @return Property
     */
    public function createProperty($name)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionProperty(User::class, $name);
        $property   = new Property($reflection);

        return $property;
    }

    /**
     * Provide type test data
     */
    public function provideTypes()
    {
        return [
            ['id', 'int'],
            ['username', 'string'],
            ['middleName', null],
            ['lastName', null],
            ['active', 'bool'],
            ['ignored', 'int'],
            ['updateDate', 'DateTime'],
            ['deletedDate', 'DateTime'],
        ];
    }

    /**
     * Test untyped property access
     *
     * @dataProvider provideTypes
     * @param string $name
     * @param mixed  $type
     */
    public function testType($name, $type)
    {
        $property = $this->createProperty($name);

        $this->assertSame($name, $property->name());
        $this->assertSame($type, $property->type());
    }

    /**
     * Provide set values
     *
     * @return array
     */
    public function provideSetData()
    {
        return [
            ['id', null, null],
            ['id', 1, 1],
            ['id', '1', 1],
            ['username', null, null],
            ['username', 'john', 'john'],
            ['lastName', null, null],
            ['lastName', 'Doe', 'Doe'],
            ['lastName', 3, 3],
            ['active', null, null],
            ['active', '0', false],
            ['active', '1', true],
            ['active', 0, false],
            ['active', 1, true],
            ['ignored', null, null],
            ['ignored', '0', 0],
            ['ignored', '1', 1],
            ['ignored', 0, 0],
            ['ignored', 1, 1],
            ['updateDate', null, null],
            ['updateDate', '2001-02-03', new DateTime('2001-02-03 00:00:00')],
            ['updateDate', '2001-02-03 04:05:06', new DateTime('2001-02-03 04:05:06')],
        ];
    }

    /**
     * Test set value
     *
     * @dataProvider provideSetData
     * @param string $name
     * @param mixed  $in
     * @param mixed  $out
     */
    public function testSet($name, $in, $out)
    {
        $user = new User;

        $property = $this->createProperty($name);
        $property->set($user, $in);

        if ($out instanceof DateTime) {
            $this->assertEquals($out, $user->$name);
        } else {
            $this->assertSame($out, $user->$name);
        }
    }

    /**
     * Provide get values
     *
     * @return array
     */
    public function provideGetData()
    {
        return [
            ['id', null, null],
            ['id', 1, 1],
            ['id', '1', 1],
            ['username', null, null],
            ['username', 'john', 'john'],
            ['lastName', null, null],
            ['lastName', 'Doe', 'Doe'],
            ['lastName', 3, 3],
            ['active', null, null],
            ['active', false, 0],
            ['active', true, 1],
            ['ignored', null, null],
            ['ignored', '0', 0],
            ['ignored', '1', 1],
            ['ignored', 0, 0],
            ['ignored', 1, 1],
            ['updateDate', null, null],
            ['updateDate', '2001-02-03', '2001-02-03 00:00:00'],
            ['updateDate', new DateTime('2001-02-03 04:05:06'), '2001-02-03 04:05:06'],
        ];
    }

    /**
     * Test get value
     *
     * @dataProvider provideGetData
     * @param string $name
     * @param mixed  $in
     * @param mixed  $out
     */
    public function testGet($name, $in, $out)
    {
        $user        = new User;
        $user->$name = $in;

        $property = $this->createProperty($name);

        if ($out instanceof DateTime) {
            $this->assertEquals($out, $property->get($user));
        } else {
            $this->assertSame($out, $property->get($user));
        }
    }
}
