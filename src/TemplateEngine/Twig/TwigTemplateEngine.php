<?php
declare(strict_types=1);

namespace PhpGc\TemplateEngine\Twig;

use PhpGc\TemplateEngine\TemplateEngine;
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
        return $this->twig->render(self::TEMPLATE_NAME, [
            'difinition' => $difinition
        ]);
    }
}
