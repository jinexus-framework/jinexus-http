# JiNexus Http

`JiNexus Http` provides an object-oriented interface over PHP's HTTP request
super-globals — `$_GET`, `$_POST`, `$_FILES`, `$_COOKIE`, and `$_SERVER` — along with
convenient access to common request information such as whether the request is an Ajax
call, whether it is served over HTTPS, and the base URL.

Request data is injected at construction and falls back to the super-globals when a key
is omitted, so the component is easy to test and does not read global state once you pass
it an explicit request array.

- Documentation: https://framework.jinexus.com/documentation
- Issues: https://github.com/jinexus-framework/jinexus-http/issues

## Requirements

- PHP `^8.5`

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require jinexus-framework/jinexus-http
```

## Usage

### Creating a request

```php
use JiNexus\Http\Request\Request;
use JiNexus\Http\Request\Factory\RequestFactory;

// From the current super-globals...
$request = new Request();

// ...or via the factory.
$request = RequestFactory::build();

// ...or with explicit data (ideal for tests and CLI contexts).
$request = new Request([
    'cookie' => $_COOKIE,
    'file'   => $_FILES,
    'post'   => ['name' => 'jinexus'],
    'get'    => ['page' => '2'],   // note: the 'get' key populates the query bag
    'server' => $_SERVER,
]);
```

### The parameter bags

Each of `cookie`, `file`, `post`, `query`, and `server` is a `Parameter` bag:

```php
$request->query->get('page');          // '2'
$request->query->get('missing', 1);    // 1 (default when absent)
$request->post->has('name');           // true
$request->post->all();                 // ['name' => 'jinexus']
count($request->query);                // Countable
foreach ($request->server as $k => $v) { /* IteratorAggregate */ }

$request->query->add(['sort' => 'asc']); // merge/overwrite
$request->query->remove('page');         // delete
```

> Note the mapping: `$request->query` is built from the `get` key (and `$_GET`), not from
> a `query` key.

### Request helpers

```php
$request->isAjax();    // true when the X-Requested-With header is "XMLHttpRequest"
$request->isSecure();  // true when the HTTPS server value is truthy
$request->baseUrl();   // e.g. "https://example.com/app"
```

### The HTTP container

```php
use JiNexus\Http\Http\Http;
use JiNexus\Http\Http\Factory\HttpFactory;

$http = new Http($request);
$http->request;             // the RequestInterface it was constructed with

$http = HttpFactory::build(); // builds a Request internally and wraps it
```

### Error handling

Package-level failures throw `JiNexus\Http\HttpException` (a subclass of `\Exception`).
For example, an unsupported magic getter/setter resolved through `AbstractBase::__call`
throws a `HttpException` with a `Not implemented: …` message.

## Extending

`Request` and `Http` are deliberately empty subclasses of `AbstractRequest` and
`AbstractHttp`. Keep them that way — the public accessors are implemented with PHP
property hooks over backing fields, and declaring a real property would shadow them. For
IDE autocompletion, prefer `@property` PHPDoc tags over real properties.

## Testing

```bash
composer install
composer test            # or: ./vendor/bin/phpunit
composer test:coverage   # text coverage report
```

`phpunit.dist.xml` is the committed configuration. Use `XDEBUG_MODE=off` to silence the
local Xdebug notice, and `--testdox` for readable output:

```bash
XDEBUG_MODE=off ./vendor/bin/phpunit --testdox
```

## Contributing

Please see [CONDUCT.md](CONDUCT.md) for the code of conduct. Contributions should include
tests and a `CHANGELOG.md` entry. See [AGENTS.md](AGENTS.md) for detailed build, style,
and workflow conventions.

## License

BSD-3-Clause. See [LICENSE.md](LICENSE.md).
