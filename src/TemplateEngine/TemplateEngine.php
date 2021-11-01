<?php
declare(strict_types=1);

namespace PhpGc\TemplateEngine;

use PumlParser\Dto\Difinition;

interface TemplateEngine
{
    public function render(Difinition $difinition): string;
}
