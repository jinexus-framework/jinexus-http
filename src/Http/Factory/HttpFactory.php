<?php
namespace JiNexus\Http\Http\Factory;

use JiNexus\Http\Factory\AbstractFactory;
use JiNexus\Http\Http\Http;
use JiNexus\Http\Request\Factory\RequestFactory;

/**
 * Class HttpFactory
 * @package JiNexus\Http\Http\Factory
 */
class HttpFactory extends AbstractFactory
{
    /**
     * @return Http
     */
    public static function build()
    {
        $request = RequestFactory::build();

        return new Http($request);
    }
}