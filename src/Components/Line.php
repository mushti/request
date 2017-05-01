<?php

namespace Raptor\Request\Components;

class Line
{
    /**
     * method
     * @see https://tools.ietf.org/html/rfc7231#section-4
     *
     * @var string
     */
    protected $method;

    /**
     * request-target
     * @see https://tools.ietf.org/html/rfc7230#section-5.3
     *
     * @var \Raptor\Request\Components\Target
     */
    protected $target;

    /**
     * HTTP-version
     *
     * @var string
     */
    protected $version;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->target = new Target;
    }

    /**
     * Get the method.
     *
     * @return  string
     */
    public function method()
    {
        if ($this->method !== null) {
            return $this->method;
        }
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
        if ($this->method === 'POST' && !empty($_SERVER['X-HTTP-METHOD-OVERRIDE'])) {
            return $this->method = strtoupper($_SERVER['X-HTTP-METHOD-OVERRIDE']);
        }
        if ($this->method === 'POST' && !empty($_POST['_method'])) {
            return $this->method = strtoupper($_POST['_method']);
        }
        return $this->method;
    }

    /**
     * Get the request-target.
     *
     * @return  \Raptor\Request\Components\Target
     */
    public function target()
    {
        return $this->target;
    }

    /**
     * Get the HTTP-version.
     *
     * @return  string
     */
    public function version()
    {
        if ($this->version !== null) {
            return $this->version;
        }
        return $this->version = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : false;
    }
}