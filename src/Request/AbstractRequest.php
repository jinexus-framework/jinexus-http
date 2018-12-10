<?php
namespace JiNexus\Http\Request;

use JiNexus\Http\Base\AbstractBase;

/**
 * Class AbstractRequest
 * @package JiNexus\Http\Request
 */
abstract class AbstractRequest extends AbstractBase implements RequestInterface
{
    /**
     * @var
     */
    protected $baseUrl;

    /**
     * @var Parameter
     */
    protected $cookie;

    /**
     * @var Parameter
     */
    protected $file;

    /**
     * @var Parameter
     */
    protected $post;

    /**
     * @var Parameter
     */
    protected $query;

    /**
     * @var Parameter
     */
    protected $server;

    /**
     * AbstractRequest constructor
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        $this->cookie = new Parameter(isset($request['cookie']) ? $request['cookie'] : $_COOKIE);
        $this->file = new Parameter(isset($request['file']) ? $request['file'] : $_FILES);
        $this->post = new Parameter(isset($request['post']) ? $request['post'] : $_POST);
        $this->query = new Parameter(isset($request['get']) ? $request['get'] : $_GET);
        $this->server = new Parameter(isset($request['server']) ? $request['server'] : $_SERVER);
    }

    /**
     * Returns the cookie data
     *
     * @return Parameter
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Returns the file data
     *
     * @return Parameter
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the post data
     *
     * @return Parameter
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Returns the query string data
     *
     * @return Parameter
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Returns the server data
     *
     * @return Parameter
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Returns true if the request was made using Ajax and false if not
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Returns true if the request was made using HTTPS and false if not
     *
     * @return bool
     */
    public function isSecure()
    {
        return filter_var($this->server->get('HTTPS', false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Returns the base url of the request
     *
     * @return string
     */
    public function baseUrl()
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