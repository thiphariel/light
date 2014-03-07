<?php
/**
 * Init the user request
 * @author Thomas Collot
 */

namespace Light\App\Core;

class Request
{
    private $url;

    /**
     * Construct the request object with the user URL
     */
    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
    }

    /**
     * Retrieve the user URL
     * @return string url
     */
    public function url()
    {
        return $this->url;
    }
}
