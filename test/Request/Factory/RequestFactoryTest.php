<?php

declare(strict_types=1);

namespace JiNexus\Http\Test\Request\Factory;

use JiNexus\Http\Request\Factory\RequestFactory;
use JiNexus\Http\Request\RequestInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequestFactory::class)]
final class RequestFactoryTest extends TestCase
{
    #[Test]
    public function it_builds_something_usable_as_a_request(): void
    {
        RequestFactory::build()
            |> class_implements(...)
            |> (fn($x) => self::assertContains(RequestInterface::class, $x));
    }

    #[Test]
    public function build_returns_a_fresh_instance_each_call(): void
    {
        self::assertNotSame(RequestFactory::build(), RequestFactory::build());
    }
}
