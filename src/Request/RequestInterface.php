<?php
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

    /**
     * Returns the cookies
     *
     * @return Parameter
     */
    public function getCookies();

    /**
     * Returns the files
     *
     * @return Parameter
     */
    public function getFiles();

    /**
     * Returns the post data
     *
     * @return Parameter
     */
    public function getPost();

    /**
     * Returns the query string
     *
     * @return Parameter
     */
    public function getQuery();

    /**
     * Returns the server
     *
     * @return Parameter
     */
    public function getServer();

    /**
     * Returns true if the request was made using Ajax and false if not
     *
     * @return bool
     */
    public function isAjax();

    /**
     * Returns true if the request was made using HTTPS and false if not
     *
     * @return bool
     */
    public function isSecure();

    /**
     * Returns the base url of the request
     *
     * @return string
     */
    public function baseUrl();
}