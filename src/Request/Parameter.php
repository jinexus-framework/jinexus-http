<?php

declare(strict_types=1);

namespace JiNexus\Http\Request;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use ReturnTypeWillChange;
use Traversable;

/**
 * Class Parameter
 * @package JiNexus\Http\Request
 */
class Parameter implements Countable, IteratorAggregate
{
    /**
     * Parameter.
     *
     * @var array
     */
    protected array $parameter;

    /**
     * Parameter constructor
     *
     * @param array $parameter
     */
    public function __construct(array $parameter = [])
    {
        $this->parameter = $parameter;
    }

    /**
     * Return the count of all parameters
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->parameter);
    }

    /**
     * Adds a parameter
     *
     * @param array $parameter
     */
    public function add(array $parameter = []): void
    {
        $this->parameter = array_replace($this->parameter, $parameter);
    }

    /**
     * Returns true if the parameter exists and false if not
     *
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->parameter);
    }

    /**
     * Gets a parameter value
     *
     * @param $key
     * @param mixed $default
     * @return mixed|null
     */
    public function get($key, mixed $default = null): mixed
    {
        if (! array_key_exists($key, $this->parameter))
        {
            return $default;
        }

        return $this->parameter[$key];
    }

    /**
     * Removes a parameter
     *
     * @param $key
     */
    public function remove($key): void
    {
        unset($this->parameter[$key]);
    }

    /**
     * Returns all the parameter
     *
     * @return array
     */
    public function all(): array
    {
        return $this->parameter;
    }

    /**
     * Returns an array iterator object
     *
     * @return ArrayIterator|Traversable
     */
    #[ReturnTypeWillChange]
    public function getIterator(): Traversable|ArrayIterator
    {
        return new ArrayIterator($this->parameter);
    }
}
