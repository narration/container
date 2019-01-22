<?php

namespace Tests\Unit;

use Narration\Container\Container;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testAutoConcreteResolution(): void
    {
        $container = new Container();
        static::assertInstanceOf(Foo::class, $container->get(Foo::class));
    }

    public function testClosureResolution(): void
    {
        $container = new Container([
            Foo::class => 1,
        ]);

        static::assertEquals(1, $container->get(Foo::class));

        $container = new Container([
            Foo::class => 'bar',
        ]);

        static::assertEquals('bar', $container->get(Foo::class));
    }

    public function testUsesReflection(): void
    {
        $container = new Container();
        static::assertInstanceOf(Bar::class, $container->get(Bar::class));
    }
}

final class Foo
{
}

final class Bar
{
    /**
     * @var \Tests\Unit\Foo
     */
    private $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }
}
