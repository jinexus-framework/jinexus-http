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
     * Parameter.
     *
     * @var array
     */
    protected $parameter;

    /**
     * Parameter constructor
     *
     * @param array $parameter
     */
    public function __construct($parameter = [])
    {
        $this->parameter = $parameter;
    }

    /**
     * Return the count of all parameter
     *
     * @return int
     */
    public function count()
    {
        return count($this->parameter);
    }

    /**
     * Adds a parameter
     *
     * @param $parameter
     */
    public function add($parameter = [])
    {
        $this->parameter = array_replace($this->parameter, $parameter);
    }

    /**
     * Returns true if the parameter exists and false if not
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->parameter);
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
    public function remove($key)
    {
        unset($this->parameter[$key]);
    }

    /**
     * Returns all the parameter
     *
     * @return array
     */
    public function all()
    {
        return $this->parameter;
    }

    /**
     * Returns an array iterator object
     *
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->parameter);
    }
}