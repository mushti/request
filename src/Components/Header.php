<?php

namespace Raptor\Request\Components;

class Header
{
    /**
     * header-fields (see https://tools.ietf.org/html/rfc7230#section-3.2)
     *
     * @var array
     */
    protected $fields;

    /**
     * Cookies ($_COOKIE).
     *
     * @var array
     */
    protected $cookie;

    /**
     * Authorization header-field ($_SERVER['AUTHORIZATION']).
     *
     * @var \Raptor\Request\Components\Authorization
     */
    protected $authorization;
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->fields = [];
        // CONTENT_* are not prefixed with HTTP_
        $content = ['CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true];
        foreach ($_SERVER as $key => $value) {
            if (0 === strpos($key, 'HTTP_') || isset($content[$key])) {
                $this->fields[$key] = $value;
            }
        }
        $this->authorization = new Authorization;
    }

    /**
     * Get all header-fields.
     *
     * @return array
     */
    public function all()
    {
        return $this->fields;
    }

    /**
     * Get specific header-field.
     *
     * @return string
     */
    public function get($key, $default = null)
    {
        return isset($this->fields[$key]) ? $this->fields[$key] : $default;
    }

    /**
     * Get cookies.
     *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
     */
    public function cookie($key = null, $default = null)
    {
        if ($this->cookie === null) return $this->cookie = $_COOKIE;
        if ($key === null) return $this->cookie;
        return isset($this->cookie[$key]) ? $this->cookie[$key] : $default;
    }

    /**
     * Get authorization header-field.
     *
     * @return \Raptor\Request\Components\Authorization
     */
    public function authorization()
    {
        return $this->authorization;
    }
}