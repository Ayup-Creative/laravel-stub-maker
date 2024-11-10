<?php

namespace Ayup\LaravelStubMaker\Concerns;

use Ayup\LaravelStubMaker\Stub;

trait MakesStubs
{
    protected function stub(?string $objectName = null): Stub
    {
        $stub = new Stub;

        if(!is_null($objectName)) {
            [$namespace, $objectName] = $this->breakIntoNamespace($objectName);
            $stub->className($objectName)
                ->namespace($namespace);
        }

        return $stub;
    }

    protected function breakIntoNamespace(string $path): array
    {
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        $class = $parts[count($parts) - 1];

        $parts = array_reverse($parts);
        array_shift($parts);
        $parts = array_reverse($parts);

        return [
            implode('\\', $parts) ?: null,
            $class
        ];
    }
}
