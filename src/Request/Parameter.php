<?php
namespace JiNexus\Http\Request;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class Parameter
 * @package JiNexus\Http\Request
 */
class Parameter implements Countable, IteratorAggregate
{
    /**
     * Parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Parameters constructor
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Return the count of all paramenters
     *
     * @return int
     */
    public function count()
    {
        return count($this->parameters);
    }

    /**
     * Adds a parameter
     *
     * @param $parameters
     */
    public function add($parameters = [])
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    /**
     * Returns true if the parameter exists and false if not
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name)
    {
        return array_key_exists($key, $this->parameters);
    }

    /**
     * Gets a parameter value
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (! array_key_exists($key, $this->parameters))
        {
            return $default;
        }

        return $this->parameters[$key];
    }

    /**
     * Removes a parameter
     *
     * @param string $name
     */
    public function remove(string $name)
    {
        unset($this->parameters[$key]);
    }

    /**
     * Returns all the parameters
     *
     * @return array
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * Retruns an array iterator object
     *
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->parameters);
    }
}