<?php
declare(strict_types=1);

namespace Puml2Php\TemplateEngine;

use PumlParser\Dto\Difinition;

interface TemplateEngine
{
    public function render(Difinition $difinition): string;
}
