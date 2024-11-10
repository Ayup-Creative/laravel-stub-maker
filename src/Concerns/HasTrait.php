<?php

namespace Ayup\LaravelStubMaker\Concerns;

use Illuminate\Support\Arr;

trait HasTrait
{
    /**
     * Traits.
     *
     * @var array
     */
    protected array $traits = [];

    /**
     * Add traits for the class to use.
     *
     * @param string ...$traits
     * @return static
     */
    public function uses(string ...$traits): static
    {
        $this->traits = Arr::map($traits, fn ($trait) => str_replace(DIRECTORY_SEPARATOR, '\\', $trait));
        return $this;
    }
}
