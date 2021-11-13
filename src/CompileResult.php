<?php
declare(strict_types=1);

namespace Puml2Php;

class CompileResult
{
    public const CREATED = 'created';
    public const SKIP    = 'skip';

    private function __construct(private string $resultType, private string $filePath)
    {
        assert(in_array($resultType, [
            self::CREATED,
            self::SKIP
        ]));
    }

    public static function created(string $filePath): self
    {
        return new self(self::CREATED, $filePath);
    }

    public static function skip(string $filePath): self
    {
        return new self(self::SKIP, $filePath);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getType(): string
    {
        return $this->resultType;
    }
}
