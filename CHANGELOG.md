# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## v1.1.0 - 2026-07-30

### Added

- `Request`/`AbstractRequest` exposing five `Parameter` bags (`cookie`, `file`, `post`, `query`, `server`) built from an injected request array with the PHP super-globals as fallback.
- Request helpers: `isAjax()`, `isSecure()`, and `baseUrl()`.
- `Parameter` value object implementing `Countable` and `IteratorAggregate`, with `get()` (default-aware), `has()`, `add()`, `remove()`, `all()`, `count()`, and `getIterator()`.
- `File` (extends `Parameter`) and `UploadFile` (extends `SplFileInfo`) markers.
- `Http`/`AbstractHttp` container exposing the wrapped `RequestInterface` via a read-only property.
- `HttpFactory::build()` and `RequestFactory::build()` for constructing instances.
- Reflection-based magic getters/setters for public properties via `AbstractBase::__call()`.
- `HttpException` for package-level error handling.
- `HttpInterface`, `RequestInterface`, `BaseInterface`, and `FactoryInterface` contracts.
- PHPUnit 13 unit-test suite covering `Parameter`, `Request`, `Http`, the factories, and `AbstractBase`, with a committed `phpunit.dist.xml`.
- GitHub Actions CI workflow (`.github/workflows/php.yml`) that validates `composer.json`, installs dependencies on PHP 8.5, and runs the test suite.
- `composer test` / `composer test:coverage` scripts.
- `AGENTS.md` with build, coding-standard, architecture, and workflow guidance.
- Expanded `README.md` with installation, usage, and testing documentation.

### Changed

- Raised the minimum PHP requirement to `^8.5`; public request/HTTP accessors are implemented with PHP property hooks.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## v1.0.0 - 2018-07-10

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
