<?php


namespace Neat\Object\Test;

use Neat\Object\ArrayConversion;
use Neat\Object\Entity;

class Group extends Entity
{
    use ArrayConversion;

    const REPOSITORY = GroupRepository::class;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;
}
