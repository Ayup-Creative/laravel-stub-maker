<?php

namespace Ayup\LaravelStubMaker\Concerns;

use Ayup\LaravelStubMaker\Constructor;

trait HasConstructor
{
    /**
     * Object constructor.
     *
     * @var Constructor|null
     */
    protected ?Constructor $constructor = null;

    /**
     * Set the constructor.
     *
     * @param Constructor $constructor
     * @return $this
     */
    public function constructor(Constructor $constructor): static
    {
        $this->constructor = $constructor;
        return $this;
    }
}
