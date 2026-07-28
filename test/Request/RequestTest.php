<?php

declare(strict_types=1);

namespace JiNexus\Http\Test\Request;

use JiNexus\Http\Request\AbstractRequest;
use JiNexus\Http\Request\Parameter;
use JiNexus\Http\Request\Request;
use JiNexus\Http\Request\RequestInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Request::class)]
#[CoversClass(AbstractRequest::class)]
final class RequestTest extends TestCase
{
    #[Test]
    public function it_is_a_request_interface(): void
    {
        self::assertContains(RequestInterface::class, class_implements(Request::class));
    }

    #[Test]
    public function it_builds_a_parameter_bag_for_every_super_global(): void
    {
        // With no arguments it falls back to the PHP super-globals; we only
        // assert the shape (five-Parameter bags), not environment-specific data.
        $request = new Request();

        self::assertInstanceOf(Parameter::class, $request->cookie);
        self::assertInstanceOf(Parameter::class, $request->file);
        self::assertInstanceOf(Parameter::class, $request->post);
        self::assertInstanceOf(Parameter::class, $request->query);
        self::assertInstanceOf(Parameter::class, $request->server);
    }

    #[Test]
    public function it_populates_each_bag_from_the_injected_request(): void
    {
        $request = new Request([
            'cookie' => ['sid' => 'abc'],
            'file'   => ['upload' => 'f'],
            'post'   => ['name' => 'jinexus'],
            'get'    => ['page' => '2'],
            'server' => ['HTTP_HOST' => 'example.com'],
        ]);

        self::assertSame('abc', $request->cookie->get('sid'));
        self::assertSame('f', $request->file->get('upload'));
        self::assertSame('jinexus', $request->post->get('name'));
        self::assertSame('example.com', $request->server->get('HTTP_HOST'));
    }

    #[Test]
    public function query_bag_is_populated_from_the_get_key(): void
    {
        // Quirk worth locking down: the query bag reads the 'get' key,
        // not a 'query' key.
        $request = new Request(['get' => ['page' => '2', 'sort' => 'asc']]);

        self::assertSame('2', $request->query->get('page'));
        self::assertSame('asc', $request->query->get('sort'));
    }

    #[Test]
    public function is_ajax_is_true_only_for_the_xml_http_request_header(): void
    {
        $ajax = new Request(['server' => ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']]);
        self::assertTrue($ajax->isAjax());

        $plain = new Request(['server' => ['HTTP_X_REQUESTED_WITH' => 'something-else']]);
        self::assertFalse($plain->isAjax());

        $missing = new Request(['server' => []]);
        self::assertFalse($missing->isAjax());
    }

    #[Test]
    public function is_secure_reads_the_https_server_value(): void
    {
        self::assertTrue(new Request(['server' => ['HTTPS' => 'on']])->isSecure());
        self::assertTrue(new Request(['server' => ['HTTPS' => '1']])->isSecure());
        self::assertFalse(new Request(['server' => ['HTTPS' => 'off']])->isSecure());
        self::assertFalse(new Request(['server' => []])->isSecure());
    }

    #[Test]
    public function base_url_builds_an_http_url_from_the_host_and_script_path(): void
    {
        $request = new Request(['server' => [
            'HTTP_HOST'   => 'example.com',
            'SCRIPT_NAME' => '/app/index.php',
        ]]);

        self::assertSame('http://example.com/app', $request->baseUrl());
    }

    #[Test]
    public function base_url_uses_https_when_the_request_is_secure(): void
    {
        $request = new Request(['server' => [
            'HTTPS'       => 'on',
            'HTTP_HOST'   => 'secure.test',
            'SCRIPT_NAME' => '/index.php',
        ]]);

        self::assertSame('https://secure.test', $request->baseUrl());
    }

    #[Test]
    public function base_url_falls_back_to_server_name_and_appends_a_non_default_port(): void
    {
        $request = new Request(['server' => [
            'SERVER_NAME' => 'host.local',
            'SERVER_PORT' => '8080',
            'SCRIPT_NAME' => '/index.php',
        ]]);

        self::assertSame('http://host.local:8080', $request->baseUrl());
    }

    #[Test]
    public function base_url_omits_the_default_port_80(): void
    {
        $request = new Request(['server' => [
            'SERVER_NAME' => 'host.local',
            'SERVER_PORT' => '80',
            'SCRIPT_NAME' => '/index.php',
        ]]);

        self::assertSame('http://host.local', $request->baseUrl());
    }
}
