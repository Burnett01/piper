# Piper

**Piper** enhances the PHP 8.5 pipe operator (``|>``) with support for multi-argument functions/callables.

## Why?

In PHP the pipe operator (``|>``) only works with single-argument callables, such as ``strlen``, ``trim``, etc..,<br>

```php
$nonce = random_bytes(16)
      |> base64_encode(...);       
```

but what if you want to use with multi-argument callables, such as ``rtrim``, ``strtr``, etc..

```php
// does not work
$nonce = random_bytes(16)
      |> base64_encode(...)
      |> strtr(..., '+/', '-_')
      |> rtrim(..., '=');       
```

This is where **Piper** comes into play!

## How?

**Piper** is sort of a decorator/wrapper around a callable so that the pipe operator ``|>`` can use it.

### Example

```php
use Burnett01\Piper\Piper as _;

$nonce = random_bytes(16)
      |> base64_encode(...)
      |> _::_('strtr')->args('+/', '-_')->bind(...)
      |> _::with(rtrim(...))->args('=')->bind(...);       
```

As you can see, the pipe operator ``|>`` understands single-argument methods such as ``base64_encode``

and multi-argument methods such as ``strtr``, ``rtrim`` etc.

The ellipsis ``...`` represents the first-class callable syntax.

You can use a ``callable`` as string or first-class syntax for passing the method.

## Usage

> composer require burnett01/piper

```php
use Burnett01\Piper\Piper;

$nonce = random_bytes(16)
      |> base64_encode(...)
      |> Piper::_('strtr')->args('+/', '-_')->bind(...)
      |> Piper::_(rtrim(...))->args('=')->bind(...);       
```

or aliased as ``_``

```php
use Burnett01\Piper\Piper as _;

$nonce = random_bytes(16)
      |> base64_encode(...)
      |> _::_('strtr')->args('+/', '-_')->bind(...)
      |> _::_('rtrim')->args('=')->bind(...);       
```

or use ``with`` alias

```php
use Burnett01\Piper\Piper as _;

$actual = -1234.5
      |> abs(...)
      |> _::with(number_format(...))->args(2, '.', ',')->bind(...)
      |> urlencode(...);
```

## Api

#### ``Piper::_(callable $fn)``

Creates an instance of Piper for the specificed ``$fn``.

Parameters:

- callable ``$fn`` - The name of a callable as string (eg. ``'strlen'``) or as first-class syntax (eg. ``strlen(...)``)

Context: static

Returns: instance

#### ``Piper::with(callable $fn)``

alias for ``Piper::_(callable $fn)`` (see above)

#### ``$obj->args(array<mixed> $args)``

Wires the arguments for ``$fn``.

Parameters:

- array<mixed> ``$args`` - The arguments for ``$fn``

- Context: object

- Returns: $this

#### ``$obj->bind(mixed $carry)``

Binds the pipe operators carry (eg. return value of the last).

Parameters:

- mixed ``$carry`` - The pipe operators carry (eg. ``...```)

- Context: object

- Returns: mixed




