<?php

namespace Neat\Object;

use Traversable;

class Properties implements \IteratorAggregate
{
    /**
     * @var Property[]
     */
    private $columnIndexed;

    /**
     * @var Property[]
     */
    private $nameIndexed;

    /**
     * Properties constructor.
     * @param Policy   $policy
     * @param Property ...$properties
     */
    public function __construct(Policy $policy, Property ...$properties)
    {
        foreach ($properties as $property) {
            $this->columnIndexed[$policy->column($property->name())] = $property;
            $this->nameIndexed[$property->name()]                    = $property;
        }
    }

    /**
     * @param string $columnName
     * @return Property
     */
    public function byColumnName(string $columnName)
    {
        if (!isset($this->columnIndexed[$columnName])) {
            throw new \RuntimeException("Column: $columnName doesn't exist!");
        }

        return $this->columnIndexed[$columnName];
    }

    /**
     * @param string $propertyName
     * @return Property
     */
    public function byPropertyName(string $propertyName)
    {
        if (!isset($this->columnIndexed[$propertyName])) {
            throw new \RuntimeException("Property: $propertyName doesn't exist!");
        }

        return $this->nameIndexed[$propertyName];
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        foreach ($this->columnIndexed as $column => $property) {
            yield $column => $property;
        }
    }
}
