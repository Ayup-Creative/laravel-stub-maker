<?php

namespace Ayup\LaravelStubMaker\Concerns;

use Illuminate\Support\Str;

trait HasClassName
{
    /**
     * Class name.
     *
     * @var string
     */
    protected string $className = '';

    /**
     * The type of object.
     *
     * @var string
     */
    protected string $classType = 'class';

    /**
     * Set the class name for the new class.
     *
     * @param string $name
     * @return static
     */
    public function className(string $name): static
    {
        $this->className = Str::studly($name);
        return $this;
    }

    public function objectType(string $type): static
    {
        $this->classType = $type;
        return $this;
    }

    public function class(): static
    {
        $this->classType = 'class';
        return $this;
    }

    public function interface(): static
    {
        $this->classType = 'interface';
        return $this;
    }
}
