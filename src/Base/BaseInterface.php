<?php
namespace JiNexus\Http\Base;

/**
 * Interface BaseInterface
 * @package JiNexus\Http\Base
 */
interface BaseInterface
{
    /**
     * Getters and Setters
     *
     * @param $property
     * @param array $arguments
     * @return mixed
     */
    public function __call($property, array $arguments);
}
