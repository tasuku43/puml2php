<?php
declare(strict_types=1);

namespace PhpGc;

use PumlParser\Dto\Difinition;

class FilePathAssignorPsr4
{
    public function __construct(private array $psr4Map)
    {
    }

    public function assign(Difinition $difinition): string
    {
        foreach ($this->psr4Map as $namespaceRoot => $namespaceRootPath) {
            if (str_starts_with($difinition->getPackage(), $namespaceRoot)) {
                $relativePath = str_replace("\\", "/", substr($difinition->getPackage(), strlen($namespaceRoot)));

                return $namespaceRootPath[0] . '/' . $relativePath  . '/' . $difinition->getName() . '.php';
            }
        }

        throw new \InvalidArgumentException();
    }
}