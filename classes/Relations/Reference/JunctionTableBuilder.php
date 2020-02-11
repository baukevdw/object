<?php

namespace Neat\Object\Relations\Reference;

use Neat\Database\Connection;
use Neat\Object\Manager;
use Neat\Object\Property;
use Neat\Object\Relations\Reference;
use Neat\Object\Relations\ReferenceBuilder;
use Neat\Object\RepositoryInterface;

class JunctionTableBuilder implements ReferenceBuilder
{
    use Builder;

    /** @var Property */
    private $localKey;

    /** @var Property */
    private $remoteKey;

    /** @var string */
    private $remoteKeyColumn;

    /** @var RepositoryInterface */
    private $remoteRepository;

    /** @var Connection */
    private $connection;

    /** @var string */
    private $junctionTable;

    /** @var string */
    private $junctionTableLocalForeignKey;

    /** @var string */
    private $junctionTableRemoteForeignKey;

    /**
     * JunctionTableBuilder constructor.
     * @param Manager $manager
     * @param string  $local
     * @param string  $remote
     */
    public function __construct(Manager $manager, string $local, string $remote)
    {
        $policy = $manager->policy();
        $this->init($manager, $policy, JunctionTable::class);
        $localKey         = $policy->key($local);
        $remoteKey        = $policy->key($remote);
        $localForeignKey  = $policy->foreignKey($local);
        $remoteForeignKey = $policy->foreignKey($remote);

        $this->localKey                      = $this->property($local, reset($localKey));
        $this->remoteKey                     = $this->property($remote, reset($remoteKey));
        $this->remoteKeyColumn               = reset($remoteKey);
        $this->remoteRepository              = $manager->repository($remote);
        $this->connection                    = $manager->connection();
        $this->junctionTable                 = $policy->junctionTable($local, $remote);
        $this->junctionTableLocalForeignKey  = $localForeignKey;
        $this->junctionTableRemoteForeignKey = $remoteForeignKey;
    }

    /**
     * @inheritDoc
     */
    protected function build(): Reference
    {
        return new $this->class(
            $this->localKey,
            $this->remoteKey,
            $this->remoteKeyColumn,
            $this->remoteRepository,
            $this->connection,
            $this->junctionTable,
            $this->junctionTableLocalForeignKey,
            $this->junctionTableRemoteForeignKey
        );
    }

    /**
     * @param Property $localKey
     * @return self
     */
    public function setLocalKey(Property $localKey): self
    {
        $this->localKey = $localKey;

        return $this;
    }

    /**
     * @param Property $remoteKey
     * @return self
     */
    public function setRemoteKey(Property $remoteKey): self
    {
        $this->remoteKey = $remoteKey;

        return $this;
    }

    /**
     * @param string $remoteKeyColumn
     * @return self
     */
    public function setRemoteKeyColumn(string $remoteKeyColumn): self
    {
        $this->remoteKeyColumn = $remoteKeyColumn;

        return $this;
    }

    /**
     * @param RepositoryInterface $remoteRepository
     * @return self
     */
    public function setRemoteRepository(RepositoryInterface $remoteRepository): self
    {
        $this->remoteRepository = $remoteRepository;

        return $this;
    }

    /**
     * @param string $junctionTable
     * @return self
     */
    public function setJunctionTable(string $junctionTable): self
    {
        $this->junctionTable = $junctionTable;

        return $this;
    }

    /**
     * @param string $junctionTableLocalForeignKey
     * @return self
     */
    public function setJunctionTableLocalForeignKey(string $junctionTableLocalForeignKey): self
    {
        $this->junctionTableLocalForeignKey = $junctionTableLocalForeignKey;

        return $this;
    }

    /**
     * @param string $junctionTableRemoteForeignKey
     * @return self
     */
    public function setJunctionTableRemoteForeignKey(string $junctionTableRemoteForeignKey): self
    {
        $this->junctionTableRemoteForeignKey = $junctionTableRemoteForeignKey;

        return $this;
    }
}