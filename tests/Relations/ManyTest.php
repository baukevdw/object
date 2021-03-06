<?php

namespace Neat\Object\Test\Relations;

use Neat\Object\Relations\Many;
use Neat\Object\Relations\Reference;
use Neat\Object\Relations\Reference\RemoteKey;
use Neat\Object\Test\Helper\Address;
use Neat\Object\Test\Helper\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ManyTest extends TestCase
{
    /**
     * @var Reference|MockObject
     */
    private $reference;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Many
     */
    private $relation;

    /**
     * Setup before each test method
     */
    public function setUp()
    {
        $this->reference = $this->getMockBuilder(RemoteKey::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'store'])
            ->getMock();
        $this->user      = new User;
        $this->user->id  = 1;
        $this->relation  = new Many($this->reference, $this->user);
    }

    /**
     * Test get
     */
    public function testGet()
    {
        $address         = new Address;
        $address->id     = 1;
        $address->userId = 1;
        $this->reference->expects($this->once())
            ->method('load')
            ->willReturn([$address]);

        $get = $this->relation->all();
        $this->assertInternalType('array', $get);
        $this->assertSame([$address], $get);
    }

    /**
     * Test getEmpty
     */
    public function testGetEmpty()
    {
        $this->reference->expects($this->once())
            ->method('load')
            ->willReturn([]);

        $get = $this->relation->all();
        $this->assertInternalType('array', $get);
        $this->assertSame([], $get);
    }

    /**
     * Test set
     */
    public function testSet()
    {

        $address         = new Address;
        $address->id     = 1;
        $address->userId = 1;

        $this->reference->expects($this->at(0))
            ->method('store')
            ->with($this->equalTo($this->user), $this->equalTo([$address]));
        $this->reference->expects($this->at(1))
            ->method('store')
            ->with($this->equalTo($this->user), $this->equalTo([]));

        $this->relation->set([$address]);
        $this->relation->store();

        $this->relation->set([]);
        $this->relation->store();

        $this->expectException(\TypeError::class);
        $this->relation->set(null);
    }

    /**
     * Test add
     */
    public function testAdd()
    {
        $address1         = new Address;
        $address1->id     = 1;
        $address1->userId = 1;
        $address2         = new Address;
        $address2->id     = 2;
        $address2->userId = 1;
        $this->reference->expects($this->at(0))
            ->method('load')
            ->willReturn([$address1]);
        $this->reference->expects($this->at(1))
            ->method('store')
            ->with($this->equalTo($this->user), $this->equalTo([$address1, $address2]));

        $this->relation->add($address2);
        $this->relation->store();
    }
}
