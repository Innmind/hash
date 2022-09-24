<?php
declare(strict_types = 1);

namespace Tests\Innmind\Hash;

use Innmind\Hash\Hash;
use Innmind\Filesystem\{
    Adapter\Filesystem,
    Directory,
};
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class FunctionalTest extends TestCase
{
    use BlackBox;

    public function testHash()
    {
        $files = Filesystem::mount(Path::of('fixtures/'))
            ->all()
            ->toList();

        $this
            ->forAll(
                Set\Elements::of(...Hash::cases()),
                Set\Elements::of(...$files),
            )
            ->then(function($hash, $file) {
                $this->assertSame(
                    \hash_file($hash->toString(), 'fixtures/'.$file->name()->toString()),
                    $hash->ofFile($file)->hex(),
                );
            });
    }
}
