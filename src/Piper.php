<?php
declare(strict_types=1);

namespace Burnett01\Piper;

use Closure;

final class Piper
{
    private readonly Closure $fn;

    /** @var array<mixed> */
    private array $args = [];

    private function __construct(callable $fn)
    {
        $this->fn = Closure::fromCallable($fn);
    }

    public static function with(callable $fn): self
    {
        return self::_($fn);
    }

    public static function _(callable $fn): self
    {
        return new self($fn);
    }

    /** @param mixed ...$args */
    public function args(mixed ...$args): self
    {
        $this->args = [...$this->args, ...$args];

        return $this;
    }

    public function __invoke(mixed $carry): mixed
    {
        return ($this->fn)($carry, ...$this->args);
    }
}
