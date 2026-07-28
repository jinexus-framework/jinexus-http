<?php

declare(strict_types=1);

namespace JiNexus\Http\Request\Factory;

use JiNexus\Http\Factory\AbstractFactory;
use JiNexus\Http\Request\Request;

/**
 * Class RequestFactory
 * @package JiNexus\Http\Request\Factory
 */
class RequestFactory extends AbstractFactory
{
    /**
     * @return Request
     */
    public static function build(): Request
    {
        return new Request();
    }
}
