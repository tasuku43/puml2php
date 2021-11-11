<?php
declare(strict_types=1);

namespace Puml2Php;

use Puml2Php\TemplateEngine\Exception\FailedAssigningFilePath;
use PumlParser\Dto\Difinition;

class FilePathAssignorPsr4
{
    public function __construct(private array $psr4Map)
    {
    }

    /**
     * @throws FailedAssigningFilePath
     */
    public function assign(Difinition $difinition): string
    {
        foreach ($this->psr4Map as $namespaceRoot => $namespaceRootPath) {
            if ($difinition->getPackage() === str_replace("\\", "", $namespaceRoot)) {
                return $namespaceRootPath[0] . '/' . $difinition->getName() . '.php';
            }

            if (str_starts_with($difinition->getPackage(), $namespaceRoot)) {
                $relativePath = substr($difinition->getPackage(), strlen($namespaceRoot));

                $relativePath = str_replace("\\", "/", $relativePath);

                return $namespaceRootPath[0] . '/' . $relativePath  . '/' . $difinition->getName() . '.php';
            }
        }

        throw new FailedAssigningFilePath('Not found the namespace definition. Please check the definition of auroload in compser.json.');
    }
}
