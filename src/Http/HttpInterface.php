<?php
namespace JiNexus\Http\Http;

use JiNexus\Http\Base\BaseInterface;
use JiNexus\Http\Request\RequestInterface;

/**
 * Interface HttpInterface
 * @package JiNexus\Http\Http
 */
interface HttpInterface extends BaseInterface
{
    /**
     * AbstractHttp constructor
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request);

    /**
     * @return RequestInterface
     */
    public function getRequest();
}