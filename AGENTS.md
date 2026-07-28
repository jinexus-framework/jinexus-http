# AGENTS.md

Guidance for AI coding agents (and humans) working in the `jinexus-framework/jinexus-http` package. 
Read this before making changes.

## What this package is

An object-oriented wrapper around PHP's HTTP request super-globals (`$_GET`, `$_POST`,
`$_FILES`, `$_COOKIE`, `$_SERVER`), plus convenience accessors for common request
information. A `Request` exposes five `Parameter` bags and helpers like `isAjax()`,
`isSecure()`, and `baseUrl()`. `Http` is a small container that holds a `Request`. Data
is injected at construction (with the super-globals as the fallback), so the package
itself performs no global reads once you pass an explicit request array — which is what
makes it testable.

## Build & test commands

Run everything from the package root (the directory containing `composer.json`).

```bash
# Install dependencies
composer install

# Regenerate autoloader after adding/moving/renaming classes or namespaces
composer dump-autoload

# Run the full test suite (auto-discovers phpunit.dist.xml)
./vendor/bin/phpunit

# Equivalent via Composer scripts
composer test
composer test:coverage        # sets XDEBUG_MODE=coverage and prints a text report

# Silence the local Xdebug "could not connect" notice
XDEBUG_MODE=off ./vendor/bin/phpunit

# Readable, per-test output
XDEBUG_MODE=off ./vendor/bin/phpunit --testdox

# Run a single file / a single test by name (regex)
XDEBUG_MODE=off ./vendor/bin/phpunit test/Request/ParameterTest.php
XDEBUG_MODE=off ./vendor/bin/phpunit --filter base_url_omits_the_default_port_80
```

There is no build step — this is a source-only library consumed via Composer.

## Project architecture

```
src/                                Namespace: JiNexus\Http\
  HttpException.php                 Base exception (extends \Exception)
  Base/
    BaseInterface.php               Declares __call()
    AbstractBase.php                Reflection-based magic getX()/setX() for PUBLIC properties;
                                    throws HttpException on anything it can't resolve
  Http/
    HttpInterface.php               Contract: constructor(RequestInterface) + readable $request
    AbstractHttp.php                Holds a RequestInterface, exposed via a get-only property hook
    Http.php                        Concrete, intentionally EMPTY subclass of AbstractHttp
    Factory/HttpFactory.php         static build(): Http — wires in RequestFactory::build()
  Request/
    RequestInterface.php            Contract for the request bags + isAjax/isSecure/baseUrl
    AbstractRequest.php             Builds five Parameter bags; isAjax(), isSecure(), baseUrl()
    Request.php                     Concrete, intentionally EMPTY subclass of AbstractRequest
    Parameter.php                   Countable + IteratorAggregate bag:
                                    get/has/add/remove/all/count/getIterator
    File.php                        Empty subclass of Parameter (semantic marker)
    UploadFile.php                  Empty subclass of SplFileInfo (semantic marker)
    Factory/RequestFactory.php      static build(): Request
  Factory/
    FactoryInterface.php            Marker interface extending BaseInterface
    AbstractFactory.php             Base for factories (extends AbstractBase)

test/                               Namespace: JiNexus\Http\Test\
  Base/AbstractBaseTest.php         Covers AbstractBase::__call (public/protected/unknown paths)
  Request/ParameterTest.php         Covers the Parameter bag
  Request/RequestTest.php           Covers Request/AbstractRequest (mapping, isAjax/isSecure/baseUrl)
  Request/Factory/RequestFactoryTest.php
  Http/HttpTest.php                 Covers Http/AbstractHttp
  Http/Factory/HttpFactoryTest.php
  Fixture/BaseDouble.php            Test double with a public + protected property for __call tests
```

Inheritance chains: `Http` → `AbstractHttp` → `AbstractBase`; `Request` → `AbstractRequest`
→ `AbstractBase`; the factories → `AbstractFactory` → `AbstractBase`.

### Request construction contract (read before touching AbstractRequest)

`new Request($array)` reads these keys, each falling back to the matching super-global:

| Array key  | Bag (`Parameter`) | Super-global fallback |
|------------|-------------------|-----------------------|
| `cookie`   | `$request->cookie`| `$_COOKIE`            |
| `file`     | `$request->file`  | `$_FILES`             |
| `post`     | `$request->post`  | `$_POST`              |
| `get`      | `$request->query` | `$_GET`               |
| `server`   | `$request->server`| `$_SERVER`            |

Note the **`get` → `query`** mapping: the query bag is populated from the `get` key, not a
`query` key. Tests inject explicit arrays so they never depend on ambient super-globals;
keep it that way.

## Coding standards

- **Language:** PHP `^8.5`. Every PHP file starts with `declare(strict_types=1);`.
- **Autoloading:** PSR-4. `JiNexus\Http\` → `src/`, `JiNexus\Http\Test\` → `test/`. 
  One class/interface per file; the file name matches the type name.
- **Naming:** interfaces are suffixed `Interface`; abstract bases are prefixed `Abstract`.
  Namespaces mirror the directory layout.
- **Property hooks:** `AbstractRequest` and `AbstractHttp` use PHP property hooks
  (`public Parameter $cookie { get { return $this->cookie; } }`) to expose read-only
  backing fields. Keep the concrete `Request`/`Http` classes **empty** — do not declare
  real per-key properties on them. A declared property shadows the hook/backing field and
  changes access semantics. If you only need to silence an IDE "undefined property"
  notice, use a `@property` PHPDoc tag, never a real property.
- **Errors:** throw `JiNexus\Http\HttpException` (or a subclass) for package-level
  failures. `AbstractBase::__call` already throws it for unresolved magic calls.
- **PHP 8.5 features are in use.** The test suite uses newer syntax (e.g., the pipe
  operator `|>`). Keep the `php: "^8.5"` constraint in mind — do not add code requiring a
  higher version, and do not lower the floor to satisfy a tool that can't parse 8.5.

### Test conventions

- Tests extend `PHPUnit\Framework\TestCase` and are declared `final`.
- Use PHPUnit **attributes**, not annotations: `#[Test]`, `#[CoversClass(...)]`.
- Test method names are `snake_case` and describe the behavior.
- PHPUnit 13: use `expectExceptionMessageMatches()` (regex), **not** the deprecated
  `expectExceptionMessage()`. Wrap literal text with `preg_quote($text, '/')`.
- **Prefer assertions that reflect a real runtime contract over ones the type checker can
  fold to a constant.** Because this package leans on typed properties and property hooks,
  `assertInstanceOf(X::class, $typedValue)` is often always-true — assert on
  `class_implements(SomeClass::class)` instead, or assert on actual behavior/data.
- Inject request data explicitly (`new Request(['server' => [...], 'get' => [...]])`);
  never rely on ambient super-globals for deterministic assertions.
- When a test deliberately exercises magic access or calls an unsupported magic method,
  suppress the specific IDE inspection on that line only
  (`//noinspection PhpUndefinedFieldInspection` / `PhpUndefinedMethodInspection`) with a
  comment saying why — don't disable inspections globally and don't add misleading
  `@method`/`@property` tags for members that are supposed to fail.

## Workflow rules

- **Before finishing any change, run the suite** and make sure it's green: `XDEBUG_MODE=off ./vendor/bin/phpunit`.
- **After touching classes/namespaces**, run `composer dump-autoload`.
- **New behavior requires a new test.** This is a pure-logic library, so unit tests are expected to cover every 
  branch you add or change.
- **Commits:** follow [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/).
  Format: `type(scope): description`, with an optional body and footers.
  - Common types: `feat`, `fix`, `docs`, `test`, `refactor`, `chore`, `build`, `ci`.
  - Scope is optional and names the affected area (e.g. `feat(request): …`).
  - Subject is imperative and lowercase, no trailing period.
  - Breaking changes: add `!` after the type/scope (`feat!:`) and a `BREAKING CHANGE:` footer describing the break 
    and its migration.
- **Changelog:** update `CHANGELOG.md` for every user-visible change, following the Keep a Changelog structure already 
  in the file (Added / Changed / Deprecated / Removed / Fixed). Newest release on top.
- **Versioning:** semantic versioning. When bumping the minor/major line, also update `extra.branch-alias.dev-main` 
  in `composer.json` to match the next dev series.
- **Config files:** `phpunit.dist.xml` is the committed default; a local `phpunit.xml` (gitignored) overrides it for 
  personal tweaks. Don't commit `phpunit.xml`, `vendor/`, or `.phpunit.cache/`.
- **CI:** `.github/workflows/php.yml` runs on pushes and pull requests to `main` — it validates `composer.json`, 
  installs on PHP 8.5 (pinned via `shivammathur/setup-php`), and runs `composer test`. Keep the workflow's PHP version 
  in sync with the `require.php` constraint in `composer.json`.
```
