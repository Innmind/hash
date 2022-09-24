# Hash

[![Build Status](https://github.com/innmind/hash/workflows/CI/badge.svg?branch=master)](https://github.com/innmind/hash/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/innmind/hash/branch/develop/graph/badge.svg)](https://codecov.io/gh/innmind/hash)
[![Type Coverage](https://shepherd.dev/github/innmind/hash/coverage.svg)](https://shepherd.dev/github/innmind/hash)

This component allows to incrementally compute the hash of a file (or any sequence of strings).

## Installation

```sh
composer require innmind/hash
```

## Usage

```php
use Innmind\OperatingSystem\Factory;
use Innmind\Url\Path;
use Innmind\Hash\{
    Hash,
    Value,
};
use Innmind\Immutable\Set;

$hashes = Factory::build()
    ->filesystem()
    ->mount(Path::of('some-folder/'))
    ->all()
    ->map(Hash::sha512->ofFile(...));
$hashes; // Set<Value>
```

Since the computation doesn't rely on the filesystem it can be called on content that is not on the filesystem and that cannot be fitted in memory.

Examples:

```php
use Innmind\OperatingSystem\Factory;
use Innmind\Http\{
    Message\Request\Request,
    Message\Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use Innmind\Server\Control\Server\Command;
use Innmind\Hash\{
    Hash,
    Value,
};

$os = Factory::build();
$os
    ->remote()
    ->http()(new Request(
        Url::of('https://github.com'),
        Method::get,
        ProtocolVersion::v20,
    ))
    ->map(static fn($success) => $success->response()->content())
    ->map(Hash::sha512->ofContent(...))
    ->match(
        static fn($value) => $value, // Value
        static fn() => null, // http call failed
    );

$output = $os
    ->control()
    ->processes()
    ->execute(
        Command::foreground('git')
            ->withOption('version'),
    )
    ->output()
    ->chunks()
    ->map(static fn($chunk) => $chunk[0]); // to only keep the chunk data

Hash::sha512->ofSequence($output); // Value
```
