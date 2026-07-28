<?php

declare(strict_types=1);

namespace JiNexus\Http\Test\Http\Factory;

use JiNexus\Http\Http\Factory\HttpFactory;
use JiNexus\Http\Http\HttpInterface;
use JiNexus\Http\Request\RequestInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(HttpFactory::class)]
final class HttpFactoryTest extends TestCase
{
    #[Test]
    public function it_builds_something_usable_as_http(): void
    {
        HttpFactory::build()
            |> class_implements(...)
            |> (fn($x) => self::assertContains(HttpInterface::class, $x));
    }

    #[Test]
    public function it_wires_a_request_into_the_built_http(): void
    {
        self::assertInstanceOf(RequestInterface::class, HttpFactory::build()->request);
    }

    #[Test]
    public function build_returns_a_fresh_instance_each_call(): void
    {
        self::assertNotSame(HttpFactory::build(), HttpFactory::build());
    }
}
