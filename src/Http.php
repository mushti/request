<?php

namespace Raptor\Request;

use Raptor\Request\Components\Line;
use Raptor\Request\Components\Header;
use Raptor\Request\Components\Body;

class Http
{
    /**
     * request-line (see https://tools.ietf.org/html/rfc7230#section-3.1.1)
     *
     * @var \Raptor\Request\Components\Line
     */
    protected $line;
    
    /**
     * request-header (see https://tools.ietf.org/html/rfc7230#section-3.2)
     *
     * @var \Raptor\Request\Components\Header
     */
    protected $header;
    
    /**
     * message-body (see https://tools.ietf.org/html/rfc7230#section-3.3)
     *
     * @var \Raptor\Request\Components\Body
     */
    protected $body;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->line = new Line;
        $this->header = new Header;
        $this->body = new Body;
    }

    /**
     * Get the request-line.
     *
     * @return \Raptor\Request\Components\Line
     */
    public function line()
    {
        return $this->line;
    }

    /**
     * Get the request-header.
     *
     * @return \Raptor\Request\Components\Header
     */
    public function header()
    {
        return $this->header;
    }

    /**
     * Get the message-body.
     *
     * @return \Raptor\Request\Components\Body
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Get the method token from request-line.
     *
     * @return  string
     */
    public function method()
    {
        return $this->line()->method();
    }

    /**
     * Get the query string from request-target of request-line.
     *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
     */
    public function query($key = null, $default = null)
    {
        return $this->line()->target()->query($key, $default);
    }

    /**
     * Get the cookies from request-header.
     *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
     */
    public function cookie($key = null, $default = null)
    {
        return $this->header()->cookie($key, $default);
    }

    /**
     * Get the param from message-body.
     *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
     */
    public function param($key = null, $default = null)
    {
        return $this->body()->param($key, $default);
    }

    /**
     * Get the uploaded files from message-body.
     *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
     */
    public function files($key = null, $default = null)
    {
        return $this->body()->files($key, $default);
    }

    /**
     * Get the authorization header-field from request-header.
     *
     * @return  \Raptor\Request\Components\Authorization
     */
    public function auth()
    {
        return $this->header()->authorization();
    }
}