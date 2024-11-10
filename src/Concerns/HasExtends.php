<?php

namespace Ayup\LaravelStubMaker\Concerns;

trait HasExtends
{
    /**
     * Base class.
     *
     * @var string
     */
    protected string $baseClass = '';

    /**
     * Define a base class to extend.
     *
     * @param string $baseClass
     * @return static
     */
    public function extends(string $baseClass): static
    {
        $this->baseClass = str_replace(DIRECTORY_SEPARATOR, '\\', $baseClass);
        return $this;
    }
}
