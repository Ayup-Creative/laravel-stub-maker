<?php

namespace Ayup\LaravelStubMaker;

use Exception;
use Illuminate\Support\Arr;

class Argument
{
    const string PUBLIC = 'public';
    const string PRIVATE = 'private';
    const string PROTECTED = 'protected';

    /**
     * An array to keep track of uses statements.
     *
     * @var array
     */
    public array $uses = [];

    /**
     * Readonly flag.
     *
     * @var bool
     */
    protected bool $readonly = false;

    /**
     * Scope of the argument.
     *
     * @var string|null
     */
    protected ?string $scope = null;

    /**
     * Type casting arguments.
     *
     * @var string|array|null
     */
    protected null|string|array $hint = null;

    /**
     * Default value.
     *
     * @var mixed
     */
    protected mixed $default = 'NOVALUE';

    /**
     * Create a new attribute.
     *
     * @param string $name
     */
    public function __construct(
        private readonly string $name
    ) {}

    /**
     * Create a new attriute statically.
     *
     * @param string $name
     * @return $this
     */
    public static function make(string $name): static
    {
        return new static($name);
    }

    /**
     * Set the argument to readonly.
     *
     * @return $this
     */
    public function readonly(): static
    {
        $this->readonly = true;
        return $this;
    }

    /**
     * Set the scope.
     *
     * @param string $scope
     * @return $this
     * @throws Exception
     */
    public function scope(string $scope): static
    {
        if(!in_array($scope, [static::PUBLIC, static::PRIVATE, static::PROTECTED])) {
            throw new \Exception(sprintf("Scope '%s' is not allowed", $scope));
        }

        $this->scope = $scope;
        return $this;
    }

    /**
     * Set the argument to public scope.
     *
     * @return $this
     * @throws Exception
     */
    public function public(): static
    {
        return $this->scope(static::PUBLIC);
    }

    /**
     * Set the scope to private.
     *
     * @return $this
     * @throws Exception
     */
    public function private(): static
    {
        return $this->scope(static::PRIVATE);
    }

    /**
     * Set the scope to protected.
     *
     * @return $this
     * @throws Exception
     */
    public function protected(): static
    {
        return $this->scope(static::PROTECTED);
    }

    /**
     * Set the default value.
     *
     * @param mixed $value
     * @return $this
     */
    public function default(mixed $value): static
    {
        $this->default = $value;
        return $this;
    }

    /**
     * Set the type hint of the argument.
     *
     * @param string|array $type
     * @return $this
     * @throws Exception
     */
    public function hint(string|array $type): static
    {
        if(is_string($type) && str_contains($type, '|')) {
            throw new Exception('Multiple hint types should be passed as an array');
        }

        $standard = Arr::map(['string', 'mixed', 'double', 'float', 'int', 'void', 'bool', 'array', 'object'], fn ($type) => [
            str_replace(DIRECTORY_SEPARATOR, '\\', $type), '?' .  str_replace(DIRECTORY_SEPARATOR, '\\', $type)
        ]);

        $type =  str_replace(DIRECTORY_SEPARATOR, '\\', $type);

        if(!in_array($type, Arr::flatten($standard))) {
            $this->uses[] = $type;
            $type = class_basename($type);
        }

        $this->hint = $type;
        return $this;
    }

    /**
     * Get the default value.
     *
     * @return string|null
     */
    private function getDefaultValue(): ?string
    {
        if(is_null($this->default)) {
            return 'null';
        }

        if(is_object($this->default)) {
            $this->default = json_decode(json_encode($this->default), true);
        }

        if(is_array($this->default)) {
            return str_replace("\n", '', var_export($this->default, true));
        }

        if(is_string($this->default)) {
            return '"' . addslashes($this->default) . '"';
        }

        return $this->default;
    }

    public function __toString(): string
    {
        $output = [];

        if($this->readonly && is_null($this->scope)) {
            throw new Exception('Readonly properties must have a scope');
        }

        if(!is_null($this->scope)) {
            $output[] = $this->scope;

            if($this->readonly) {

                if(is_null($this->hint)) {
                    throw new Exception("Readonly properties must have a type-hint set");
                }

                $output[] = 'readonly';
            }
        }

        if(!is_null($this->hint)) {
            $output[] = is_array($this->hint)
                ? implode('|', $this->hint)
                : $this->hint;
        }

        $output[] = '$' . $this->name;

        if($this->default !== 'NOVALUE') {
            $output[] = '= ' . $this->getDefaultValue();
        }

        return implode(' ', $output);
    }
}
