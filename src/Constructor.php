<?php

namespace Ayup\LaravelStubMaker;

use Illuminate\Support\Arr;

class Constructor
{
    /**
     * Sets the indent when converting to string.
     *
     * @var int
     */
    protected int $indent = 4;

    /**
     * Make a constructor.
     *
     * @param array $arguments
     */
    public function __construct(
        public array $arguments = []
    ) {}

    /**
     * Statically make a constructor.
     *
     * @param array $arguments
     * @return static
     */
    public static function make(array $arguments): static
    {
        return new static($arguments);
    }

    /**
     * Adds constructor arguments.
     *
     * Use an array of strings to set argument variables.
     *
     * Use an array of Argument objects to get more creative.
     *
     *
     * @param string[]|Argument[] $arguments
     * @return $this
     */
    public function arguments(array $arguments): static
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * Set the indent.
     *
     * @param int $indent
     * @return $this
     */
    public function setIndent(int $indent): static
    {
        $this->indent = $indent;
        return $this;
    }

    private function getIndent(int $times = 1): string
    {
        return str_repeat(' ', $this->indent * $times);
    }

    /**
     * Checks to see if any of the arguments are of the Argument object type.
     *
     * @return bool
     */
    private function containsSimpleArgumentsOnly(): bool
    {
        return collect($this->arguments)
            ->filter(fn ($argument) => $argument instanceof Argument)
            ->isEmpty();
    }

    private function formatArguments(): array
    {
        return Arr::map($this->arguments, function ($argument) {
            if(is_string($argument) && !str_starts_with($argument, '$')) {
                return '$' . $argument;
            }

             return (string)$argument;
        });
    }

    public function build()
    {
        $arguments = $this->formatArguments();

        $parameters = count($arguments) <= 3 && $this->containsSimpleArgumentsOnly()
            ? implode(', ', $arguments)
            : "\n" . implode(",\n", Arr::map($arguments, fn($argument) => $this->getIndent(2) . $argument)) . "\n" . $this->getIndent();

        return implode("\n", [
            $this->getIndent() . 'public function __construct(' . $parameters . ')',
            $this->getIndent() . '{',
            $this->getIndent() . '}'
        ]);
    }
}
