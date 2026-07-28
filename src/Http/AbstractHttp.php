<?php

declare(strict_types=1);

namespace JiNexus\Http\Http;

use JiNexus\Http\Base\AbstractBase;
use JiNexus\Http\Request\RequestInterface;

/**
 * Class AbstractHttp
 * @package JiNexus\Http\Http
 */
abstract class AbstractHttp extends AbstractBase implements HttpInterface
{
    /**
     * @var RequestInterface
     */
    public RequestInterface $request {
        get {
            return $this->request;
        }
    }

    /**
     * AbstractHttp constructor
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }
}
