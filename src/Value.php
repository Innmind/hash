<?php
declare(strict_types = 1);

namespace Innmind\Hash;

/**
 * @psalm-immutable
 */
final class Value
{
    private string $hex;

    private function __construct(string $hex)
    {
        $this->hex = $hex;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $hex): self
    {
        return new self($hex);
    }

    public function hex(): string
    {
        return $this->hex;
    }
}
