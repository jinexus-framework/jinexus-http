<?php

declare(strict_types=1);

namespace JiNexus\Http\Request;

use JiNexus\Http\Base\BaseInterface;

/**
 * Interface HttpInterface
 * @package JiNexus\Http\Request
 */
interface RequestInterface extends BaseInterface
{
    /**
     * AbstractRequest constructor
     * @param array $request
     */
    public function __construct(array $request = []);

    public Parameter $cookie {
        get;
    }

    public Parameter $file {
        get;
    }

    public Parameter $post {
        get;
    }

    public Parameter $query {
        get;
    }

    public Parameter $server {
        get;
    }

    /**
     * Returns true if the request was made using Ajax and false if not
     *
     * @return bool
     */
    public function isAjax(): bool;

    /**
     * Returns true if the request was made using HTTPS and false if not
     *
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * Returns the base url of the request
     *
     * @return string
     */
    public function baseUrl(): string;
}
