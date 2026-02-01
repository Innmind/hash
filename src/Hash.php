<?php
declare(strict_types = 1);

namespace Innmind\Hash;

use Innmind\Filesystem\File;
use Innmind\Immutable\{
    Sequence,
    Str,
};

enum Hash
{
    case md5;
    case sha1;
    case sha256;
    case sha384;
    case sha512;

    #[\NoDiscard]
    public function ofFile(File $file): Value
    {
        return $this->ofContent($file->content());
    }

    #[\NoDiscard]
    public function ofContent(File\Content $content): Value
    {
        return $this->ofSequence(
            $content->chunks(),
        );
    }

    /**
     * @param Sequence<Str> $chunks
     */
    #[\NoDiscard]
    public function ofSequence(Sequence $chunks): Value
    {
        return $chunks
            ->reduce(
                $this->start(),
                static fn(Incremental $hash, $chunk) => $hash->add($chunk),
            )
            ->finish();
    }

    /**
     * @psalm-external-mutation-free
     */
    #[\NoDiscard]
    public function start(): Incremental
    {
        return Incremental::start($this);
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->name;
    }
}
