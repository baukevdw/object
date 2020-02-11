<?php

namespace Neat\Object\Test\Relations\Reference;

use Neat\Object\Relations\Reference\LocalKey;
use Neat\Object\Relations\Reference\LocalKeyBuilder;
use Neat\Object\Test\Helper\Address;
use Neat\Object\Test\Helper\Factory;
use Neat\Object\Test\Helper\User;
use PHPUnit\Framework\TestCase;

class LocalKeyBuilderTest extends TestCase
{
    use Factory;

    private function localKeyBuilder(): LocalKeyBuilder
    {
        return new LocalKeyBuilder($this->manager(), Address::class, User::class);
    }

    public function testBuild()
    {
        $repository = $this->repository(Address::class);
        $builder    = $this->localKeyBuilder();
        $localKey   = $builder->property(Address::class, 'street');
        $this->assertSame($builder, $builder->setLocalKey($localKey));
        $remoteKey = $builder->property(User::class, 'typeId');
        $this->assertSame($builder, $builder->setRemoteKey($remoteKey));
        $this->assertSame($builder, $builder->setRemoteKeyString('test_remote_key_column'));
        $this->assertSame($builder, $builder->setRemoteRepository($repository));

        $this->assertEquals(
            new LocalKey(
                $localKey,
                $remoteKey,
                'test_remote_key_column',
                $repository
            ),
            $builder->resolve()
        );
    }
}