<?php
declare(strict_types=1);

namespace Puml2Php;

class CompileResult
{
    public const CREATED = 'created';
    public const SKIPED  = 'skiped';
    public const FAILED  = 'failed';

    private function __construct(
        private string $resultType,
        private string $filePath = '',
        private string $errorMessage = ''
    )
    {
        assert(in_array($resultType, [
            self::CREATED,
            self::SKIPED,
            self::FAILED
        ]));
    }

    public static function created(string $filePath): self
    {
        return new self(resultType: self::CREATED, filePath: $filePath);
    }

    public static function skiped(string $filePath): self
    {
        return new self(resultType: self::SKIPED, filePath: $filePath);
    }

    public static function failure(\Throwable $exception): self
    {
        return new self(resultType: self::FAILED, errorMessage: $exception->getMessage());
    }

    public function getFilePath(): string
    {
        assert($this->filePath !== '');

        return $this->filePath;
    }

    public function getErrorMessage(): string
    {
        assert($this->errorMessage !== '');

        return $this->errorMessage;
    }

    public function getType(): string
    {
        return $this->resultType;
    }
}
