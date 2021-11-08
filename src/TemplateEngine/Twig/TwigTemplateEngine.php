<?php
declare(strict_types=1);

namespace Puml2Php\TemplateEngine\Twig;

use Puml2Php\TemplateEngine\TemplateEngine;
use PumlParser\Dto\Difinition;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigTemplateEngine implements TemplateEngine
{
    private const TEMPLATE_NAME = 'class.twig';

    private const TEMPLATE_DIR = __DIR__ . '/templates';

    public function __construct(private Environment $twig)
    {
    }

    public static function newInstance(): self
    {
        return new self(new Environment(new FilesystemLoader(self::TEMPLATE_DIR)));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(Difinition $difinition): string
    {
        var_dump($difinition->getInterfaceNames());
        return $this->twig->render(self::TEMPLATE_NAME, [
            'difinition' => $difinition,
            'use' => $this->useArray($difinition)
        ]);
    }

    /**
     * @param Difinition $difinition
     */
    private function useArray(Difinition $difinition): array
    {
        $use = [];

        foreach ($difinition->getParents() as $parent) {
            if ($difinition->getPackage() === $parent->getPackage()) continue;

            $use[] = 'use ' . $parent->getPackage() . "\\" . $parent->getName();
        }
        foreach ($difinition->getInterfaces() as $interface) {
            if ($difinition->getPackage() === $interface->getPackage()) continue;

            $use[] = 'use ' . $interface->getPackage() . "\\" . $interface->getName();
        }

        return $use;
    }
}
