<?php

declare(strict_types=1);

namespace JiNexus\Http\Test\Http;

use JiNexus\Http\Http\AbstractHttp;
use JiNexus\Http\Http\Http;
use JiNexus\Http\Http\HttpInterface;
use JiNexus\Http\Request\Request;
use JiNexus\Http\Request\RequestInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Http::class)]
#[CoversClass(AbstractHttp::class)]
final class HttpTest extends TestCase
{
    #[Test]
    public function it_is_an_http_interface(): void
    {
        self::assertContains(HttpInterface::class, class_implements(Http::class));
    }

    #[Test]
    public function it_exposes_the_request_it_was_constructed_with(): void
    {
        $request = new Request();
        $http = new Http($request);

        self::assertSame($request, $http->request);
    }

    #[Test]
    public function the_exposed_request_is_a_request_interface(): void
    {
        $http = new Http(new Request());

        self::assertInstanceOf(RequestInterface::class, $http->request);
    }
}
