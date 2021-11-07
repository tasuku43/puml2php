<?php
declare(strict_types=1);

namespace Puml2Php;

use Puml2Php\TemplateEngine\TemplateEngine;
use PumlParser\Lexer\Token\Exception\TokenException;
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
     * @throws ParserException
     * @throws TokenException
     */
    public function exec(string $pumlFilePath, bool $dryRun = false): void
    {
        fwrite(STDOUT, sprintf("Generating code from '%s'.\n\n", basename($pumlFilePath)));

        $difinitions = $this->parser->parse($pumlFilePath)->toDtos();

        foreach ($difinitions as $difinition) {
            $filePath = $this->filePathAssignor->assign($difinition);

            $dir = rtrim($filePath, $difinition->getName() . '.php');

            fwrite(STDOUT, $filePath . "\n");

            if ($dryRun) continue;

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($filePath, $this->templateEngine->render($difinition));
        }

        fwrite(STDOUT, "\nGenerating code success.\n");
    }
}
