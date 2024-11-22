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
     * Base namespace.
     *
     * @var string|null
     */
    protected ?string $baseNamespace = null;

    /**
     * Set the namespace base.
     *
     * @param string $namespaceBase
     * @return $this
     */
    public function setNamespaceBase(string $namespaceBase): static
    {
        $this->baseNamespace = $namespaceBase;
        return $this;
    }

    /**
     * Get the namespace base.
     *
     * @return string
     */
    public function getNamespaceBase(): string
    {
        return is_null($this->baseNamespace)
            ? app()->getNamespace()
            : $this->baseNamespace;
    }

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
    public function namespace(?string $name): static
    {
        $namespace = trim($this->getNamespaceBase(), '\\') . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $name);

        return $this->rawNamespace(
            rtrim($namespace, '\\')
        );
    }
}
