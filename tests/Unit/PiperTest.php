<?php
declare(strict_types=1);

namespace Burnett01\Piper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Burnett01\Piper\Piper;

use function Burnett01\Piper\pipe;

final class PiperTest extends TestCase
{
    public function testPipeOperatorWithNativeFunctionsAndPiper(): void
    {
        $actual = -1234.5
            |> abs(...)
            |> Piper::to(number_format(...), 2, '.', ',')
            |> urlencode(...);

        self::assertSame('1%2C234.50', $actual);
    }

    public function testToAlias(): void
    {
        $actual = -1234.5
            |> abs(...)
            |> Piper::to(number_format(...), 2, '.', ',')
            |> urlencode(...);

        self::assertSame('1%2C234.50', $actual);
    }

    public function testNativeStrlen(): void
    {
        $p = Piper::with(strlen(...));
        self::assertSame(5, $p('hello'));
    }

    public function testNativeUrlencode(): void
    {
        $p = Piper::with('urlencode');
        self::assertSame('a+b%2Bc', $p('a b+c'));
    }

    public function testNativeRtrimWithCharMask(): void
    {
        $p = Piper::with(rtrim(...), " \t\n");
        self::assertSame('hello', $p("hello \n\t "));
    }

    public function testNativeSubstrWithStartAndLength(): void
    {
        $p = Piper::with(substr(...), 1, 3);
        self::assertSame('bcd', $p('abcdef'));
    }

    public function testNativeNumberFormatWithMultipleArgs(): void
    {
        $p = Piper::with(number_format(...), 2, '.', ',');
        self::assertSame('1,234.50', $p(1234.5));
    }

    public function testCallsCallableWithCarryFirstThenArgs(): void
    {
        $p = Piper::with(
            static function (int $carry, int $a, int $b): int {
                return $carry + $a + $b;
            },
            2,
            3,
        );

        self::assertSame(6, $p(1));
    }

    public function testArgsCanBeAddedOverMultipleCallsAndPreservesOrder(): void
    {
        $p = Piper::with(
            static function (string $carry, string ...$parts): string {
                return $carry . implode('', $parts);
            },
            'A',
            'B',
            'C',
        );

        self::assertSame('xABC', $p('x'));
    }

    public function testWorksWithNoArgs(): void
    {
        $p = Piper::with(
            static function (string $carry): string {
                return strtoupper($carry);
            }
        );

        self::assertSame('HELLO', $p('hello'));
    }

    public function testCallableCanBeAnInvokableObject(): void
    {
        $invokable = new class {
            public function __invoke(int $carry, int $x): int
            {
                return $carry * $x;
            }
        };

        $p = Piper::with($invokable, 5);

        self::assertSame(10, $p(2));
    }

    public function testPipeFunction(): void
    {
        $actual = -1234.5
            |> abs(...)
            |> pipe(number_format(...), 2, '.', ',')
            |> urlencode(...);

        self::assertSame('1%2C234.50', $actual);
    }
}
