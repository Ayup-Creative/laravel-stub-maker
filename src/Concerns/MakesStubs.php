<?php

namespace Ayup\LaravelStubMaker\Concerns;

use Ayup\LaravelStubMaker\Stub;

trait MakesStubs
{
    protected function stub(?string $objectName = null, ?string $namespace = null): Stub
    {
        $stub = new Stub;

        if(!is_null($namespace)) {
            $stub->setNamespaceBase($namespace);
        }

        if(!is_null($objectName)) {
            [$ns, $objectName] = $this->breakIntoNamespace($objectName);
            $stub->className($objectName)
                ->namespace($ns);
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
