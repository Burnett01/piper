<?php
declare(strict_types=1);

namespace Burnett01\Piper;

use Closure;

final class Piper
{
    private readonly Closure $fn;
    private array $args = [];

    public static function with(callable $fn): self
    {
        return self::_($fn);
    }
    
    public static function _(callable $fn): self
    {
        $self = new self();
        $self->fn = Closure::fromCallable($fn);
        
        return $self;
    }
    
    public function args(mixed ...$args): self
    {
        $this->args = [...$this->args, ...$args];
        
        return $this;
    }
    
    public function bind(mixed $carry): mixed
    {
        return ($this->fn)($carry, ...$this->args);
    }
}
