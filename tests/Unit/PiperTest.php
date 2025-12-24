<?php
declare(strict_types=1);

namespace Burnett01\Piper\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Burnett01\Piper\Piper as _;

final class PiperTest extends TestCase
{
    public function testPipeOperatorWithNativeFunctionsAndPiper(): void
    {
        $actual = -1234.5
            |> abs(...)
            |> _::_(number_format(...))->args(2, '.', ',')->bind(...)
            |> urlencode(...);

        self::assertSame('1%2C234.50', $actual);
    }

    public function testWithAlias(): void
    {
        $actual = -1234.5
            |> abs(...)
            |> _::with(number_format(...))->args(2, '.', ',')->bind(...)
            |> urlencode(...);

        self::assertSame('1%2C234.50', $actual);
    }

    public function testNativeStrlen(): void
    {
        $p = _::_(strlen(...));
        self::assertSame(5, $p->bind('hello'));
    }

    public function testNativeUrlencode(): void
    {
        $p = _::_('urlencode');
        self::assertSame('a+b%2Bc', $p->bind('a b+c'));
    }

    public function testNativeRtrimWithCharMask(): void
    {
        $p = _::_(rtrim(...))->args(" \t\n");
        self::assertSame('hello', $p->bind("hello \n\t "));
    }

    public function testNativeSubstrWithStartAndLength(): void
    {
        $p = _::_(substr(...))->args(1, 3);
        self::assertSame('bcd', $p->bind('abcdef'));
    }

    public function testNativeNumberFormatWithMultipleArgs(): void
    {
        $p = _::_(number_format(...))->args(2, '.', ',');
        self::assertSame('1,234.50', $p->bind(1234.5));
    }

    public function testBindCallsCallableWithCarryFirstThenArgs(): void
    {
        $p = _::_(
            static function (int $carry, int $a, int $b): int {
                return $carry + $a + $b;
            }
        )->args(2, 3);

        self::assertSame(6, $p->bind(1));
    }

    public function testArgsCanBeAddedOverMultipleCallsAndPreservesOrder(): void
    {
        $p = _::_(
            static function (string $carry, string ...$parts): string {
                return $carry . implode('', $parts);
            }
        )
            ->args('A')
            ->args('B', 'C');

        self::assertSame('xABC', $p->bind('x'));
    }

    public function testBindWorksWithNoArgs(): void
    {
        $p = _::_(
            static function (string $carry): string {
                return strtoupper($carry);
            }
        );

        self::assertSame('HELLO', $p->bind('hello'));
    }

    public function testCallableCanBeAnInvokableObject(): void
    {
        $invokable = new class {
            public function __invoke(int $carry, int $x): int
            {
                return $carry * $x;
            }
        };

        $p = _::_($invokable)->args(5);

        self::assertSame(10, $p->bind(2));
    }
}
