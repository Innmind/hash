<?php
declare(strict_types = 1);

namespace Tests\Innmind\Hash;

use Innmind\Hash\Hash;
use Innmind\Filesystem\Adapter\Filesystem;
use Innmind\Url\Path;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    Set,
};

class FunctionalTest extends TestCase
{
    use BlackBox;

    public function testHash(): BlackBox\Proof
    {
        $files = Filesystem::mount(Path::of('fixtures/'))
            ->root()
            ->all()
            ->toList();

        return $this
            ->forAll(
                Set::of(...Hash::cases()),
                Set::of(...$files),
            )
            ->prove(function($hash, $file) {
                $this->assertSame(
                    \hash_file($hash->toString(), 'fixtures/'.$file->name()->toString()),
                    $hash->ofFile($file)->hex(),
                );
                $this->assertSame(
                    \hex2bin(\hash_file($hash->toString(), 'fixtures/'.$file->name()->toString())),
                    $hash->ofFile($file)->binary(),
                );
            });
    }
}
