<?php
declare(strict_types=1);

namespace Burnett01\Piper;

use Closure;

final readonly class Piper
{
    /** @param array<mixed> $args */
    private function __construct(
        private readonly Closure $fn,
        private readonly array $args,
    ) {
    }

    /** @param mixed ...$args */
    public static function with(callable $fn, mixed ...$args): self
    {
        return self::to($fn, ...$args);
    }

    /** @param mixed ...$args */
    public static function to(callable $fn, mixed ...$args): self
    {
        return new self(Closure::fromCallable($fn), $args);
    }

    public function __invoke(mixed $carry): mixed
    {
        return ($this->fn)($carry, ...$this->args);
    }
}

function pipe(callable $fn, mixed ...$args): Piper
{
    return Piper::to($fn, ...$args);
}
