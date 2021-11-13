<?php
declare(strict_types=1);

namespace Puml2Php;

use Puml2Php\TemplateEngine\Exception\FailedAssigningFilePath;
use Puml2Php\TemplateEngine\TemplateEngine;
use PumlParser\Lexer\Token\Exception\TokenizeException;
use PumlParser\Parser\Exception\ParserException;
use PumlParser\Parser\Parser;

class Puml2PhpCompiler
{
    /**
     * Puml2PhpCompiler constructor.
     * @param TemplateEngine $templateEngine
     * @param FilePathAssignorPsr4 $filePathAssignor
     * @param Parser $parser
     */
    public function __construct(
        private TemplateEngine $templateEngine,
        private FilePathAssignorPsr4 $filePathAssignor,
        private Parser $parser
    )
    {
    }

    /**
     * @param string $pumlFilePath
     * @param bool $dryRun
     * @return string[]
     *
     * @throws TokenizeException|ParserException|FailedAssigningFilePath
     */
    public function exec(string $pumlFilePath, bool $dryRun = false): array
    {
        $difinitions = $this->parser->parse($pumlFilePath)->toDtos();

        $result = [];

        foreach ($difinitions as $difinition) {
            $filePath = $this->filePathAssignor->assign($difinition);

            if (file_exists($filePath)) {
                $result[] = CompileResult::skiped($filePath);
                continue;
            }

            if (!$dryRun) {
                $dir = rtrim($filePath, $difinition->getName() . '.php');

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                file_put_contents($filePath, $this->templateEngine->render($difinition));
            }

            $result[] = CompileResult::created($filePath);
        }

        return $result;
    }
}
