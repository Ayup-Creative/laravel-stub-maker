<?php

namespace Ayup\LaravelStubMaker\Concerns;

trait HasNamespace
{
    /**
     * Namespace.
     *
     * @var string
     */
    protected string $namespace = '';

    /**
     * Define the namespace for the new class.
     *
     * @param string $namespace
     * @return static
     */
    public function rawNamespace(string $namespace): static
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Set the namespace using the name argument.
     *
     * @param string $name
     * @return $this
     */
    public function namespace(string $name): static
    {
        return $this->rawNamespace(
            trim(app()->getNamespace(), '\\') . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $name)
        );
    }
}
