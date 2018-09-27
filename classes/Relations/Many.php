<?php

namespace Neat\Object\Relations;

class Many extends Relation
{
    /**
     * @return object[]
     */
    public function get(): array
    {
        if (!$this->loaded()) {
            $this->load();
        }

        return $this->objects;
    }

    /**
     * @param object[] $remotes
     * @return $this
     */
    public function set(array $remotes): self
    {
        $this->loaded  = true;
        $this->objects = $remotes;

        return $this;
    }

    /**
     * @param object $remote
     * @return $this
     */
    public function add($remote): self
    {
        $this->get();
        $this->objects[] = $remote;

        return $this;
    }
}
