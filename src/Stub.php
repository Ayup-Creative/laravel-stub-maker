<?php

namespace Ayup\LaravelStubMaker;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Stub
{
    use Concerns\HasClassName,
        Concerns\HasConstructor,
        Concerns\HasExtends,
        Concerns\HasInterface,
        Concerns\HasNamespace,
        Concerns\HasTrait,
        Concerns\Outputs;

    protected array $aliases = [];

    /**
     * Convert the stub to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFqn();
    }

    /**
     * Get the FQN of the generated object.
     *
     * @return string
     */
    public function getFqn(): string
    {
        return implode('\\', [
            $this->namespace,
            $this->className
        ]);
    }

    /**
     * Generate the class or interface file.
     *
     * @return bool
     * @throws Exception
     */
    public function writeOut(): bool
    {
        if(is_null($this->outputPath)) {
            throw new \Exception('An output path must be specified in order to write a stub to the file system');
        }

        File::ensureDirectoryExists($this->outputPath);

        $content = $this->output();

        if (!File::exists($this->outputPath)) {
            File::makeDirectory($this->outputPath, 0755, true);
        }

        $filePath = $this->outputPath . DIRECTORY_SEPARATOR . $this->className . ".php";
        return File::put($filePath, $content);
    }

    /**
     * Build the class content as a string.
     *
     * @return string
     */
    public function output(): string
    {
        $namespaceLine = $this->namespace ? "\nnamespace {$this->namespace};\n" : '';
        $useStatements = array_unique(array_merge(
            $this->interfaces,
            $this->traits,
            [$this->baseClass],
            $this->aliases
        ));
        $useLines = array_filter(array_map(fn($use) => $use ? "use {$use};" : '', $useStatements));
        if(count($this->constructor?->arguments ?? []) > 0) {
            $useLines = array_merge($useLines, Arr::map($this->constructor->arguments, function ($argument) {
                if(!$argument instanceof Argument) {
                    return null;
                }

                return Arr::map($argument->uses, fn ($use) => 'use ' . $use . ';');
            }));
            $useLines = collect($useLines)->reject(fn ($line) => blank($line))->unique()->flatten()->toArray();
        }

        $classType = $this->classType;
        $useLines = implode("\n", $useLines);
        $extendsLine = $this->baseClass ? " extends " . class_basename($this->baseClass)  : '';
        $implementsLine = $this->interfaces ? ' implements ' . implode(', ', Arr::map($this->interfaces, fn ($interface) => class_basename($interface))) : '';
        $traitsLine = $this->traits ? "\n    use " . implode(', ', Arr::map($this->traits, fn ($trait) => class_basename($trait))) . ";\n" : "";

        $constructor = $this->constructor?->build() ?? null;
        $constructor = !blank($constructor) ? "\n" . $constructor . "\n" : null;

        return <<<PHP
<?php
{$namespaceLine}
{$useLines}

{$classType} {$this->className}{$extendsLine}{$implementsLine}
{{$traitsLine}{$constructor}}
PHP;
    }
}
