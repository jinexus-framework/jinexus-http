<?php

declare(strict_types=1);

namespace JiNexus\Http\Request;

use JiNexus\Http\Base\AbstractBase;

/**
 * Class AbstractRequest
 * @package JiNexus\Http\Request
 */
abstract class AbstractRequest extends AbstractBase implements RequestInterface
{
    /**
     * @var string
     */
    protected string $baseUrl;

    /**
     * @var Parameter
     */
    public Parameter $cookie {
        get {
            return $this->cookie;
        }
    }

    /**
     * @var Parameter
     */
    public Parameter $file {
        get {
            return $this->file;
        }
    }

    /**
     * @var Parameter
     */
    public Parameter $post {
        get {
            return $this->post;
        }
    }

    /**
     * @var Parameter
     */
    public Parameter $query {
        get {
            return $this->query;
        }
    }

    /**
     * @var Parameter
     */
    public Parameter $server {
        get {
            return $this->server;
        }
    }

    /**
     * AbstractRequest constructor
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        $this->cookie = new Parameter($request['cookie'] ?? $_COOKIE);
        $this->file = new Parameter($request['file'] ?? $_FILES);
        $this->post = new Parameter($request['post'] ?? $_POST);
        $this->query = new Parameter($request['get'] ?? $_GET);
        $this->server = new Parameter($request['server'] ?? $_SERVER);
    }

    /**
     * Returns true if the request was made using Ajax and false if not
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Returns true if the request was made using HTTPS and false if not
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return filter_var($this->server->get('HTTPS', false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Returns the base url of the request
     *
     * @return string
     */
    public function baseUrl(): string
    {
        if(empty($this->baseUrl))
        {
            // Get the protocol
            $protocol = $this->isSecure() ? 'https://' : 'http://';

            // Get the server name and port
            if(($host = $this->server->get('HTTP_HOST')) === null)
            {
                $host = $this->server->get('SERVER_NAME');
                $port = $this->server->get('SERVER_PORT');

                if($port !== null && $port != 80)
                {
                    $host = $host . ':' . $port;
                }
            }

            // Get the base path
            $path = $this->server->get('SCRIPT_NAME');

            $path = str_replace(basename($path), '', $path);

            // Put them all together
            $this->baseUrl = rtrim($protocol . $host . $path, '/');
        }

        return $this->baseUrl;
    }
}
