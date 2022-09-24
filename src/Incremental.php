<?php
declare(strict_types = 1);

namespace Innmind\Hash;

use Innmind\Immutable\Str;

final class Incremental
{
    private \HashContext $context;

    private function __construct(\HashContext $context)
    {
        $this->context = $context;
    }

    public static function start(Hash $hash): self
    {
        return new self(\hash_init($hash->toString()));
    }

    /**
     * This method is not pure and return the same instance
     *
     * It return itself for ease of use in a reduce call
     *
     * @psalm-external-mutation-free
     */
    public function add(Str $chunk): self
    {
        $_ = \hash_update($this->context, $chunk->toString());

        return $this;
    }

    /**
     * Never call this method twice on the same object
     *
     * @psalm-external-mutation-free
     */
    public function finish(): Value
    {
        return Value::of(\hash_final($this->context));
    }
}
