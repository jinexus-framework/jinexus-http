<?php
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
    protected $request;

    /**
     * AbstractHttp constructor
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}