<?php

namespace Ayup\LaravelStubMaker\Concerns;

trait HasInterface
{
    /**
     * Interfaces.
     *
     * @var array
     */
    protected array $interfaces = [];

    /**
     * Add interfaces for the class to implement.
     *
     * @param string ...$interfaces
     * @return static
     */
    public function implements(string ...$interfaces): static
    {
        $this->interfaces = str_replace(DIRECTORY_SEPARATOR, '\\', $interfaces);
        return $this;
    }
}
