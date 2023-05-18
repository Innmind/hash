<?php
declare(strict_types = 1);

namespace Innmind\Hash;

/**
 * @psalm-immutable
 */
final class Value
{
    /** @var non-empty-string */
    private string $hex;

    /**
     * @param non-empty-string $hex
     */
    private function __construct(string $hex)
    {
        $this->hex = $hex;
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $hex
     */
    public static function of(string $hex): self
    {
        return new self($hex);
    }

    /**
     * @return non-empty-string
     */
    public function hex(): string
    {
        return $this->hex;
    }

    public function binary(): string
    {
        return \hex2bin($this->hex);
    }
}
