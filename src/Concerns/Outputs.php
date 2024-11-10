<?php

namespace Ayup\LaravelStubMaker\Concerns;

trait Outputs
{
    /**
     * Output destination.
     *
     * @var string
     */
    protected ?string $outputPath = null;

    /**
     * Define the output path for the generated file.
     *
     * @param string $path
     * @return static
     */
    public function outputPath(string $path): static
    {
        $this->outputPath = $path;
        return $this;
    }
}
