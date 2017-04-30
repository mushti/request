<?php

namespace Raptor\Request\Components;

class Authorization
{
	/**
	 * Authorization type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Authorization username.
	 *
	 * @var string
	 */
	protected $username;

    /**
     * Authorization password.
     *
     * @var string
     */
    protected $password;

    /**
     * Authorization token.
     *
     * @var string
     */
    protected $token;

    /**
     * Authorization header-field.
     *
     * @var string
     */
    protected $field;

	/**
	 * Get authorization type.
	 *
	 * @var string
	 */
	public function type()
	{
        if ($this->type !== null) return $this->type;
        if ($this->field() === false || count($string = explode(' ', $this->field)) < 2) return $this->type = false;
        return $this->type = $string[0];
	}

	/**
	 * Get authorization username.
	 *
	 * @var string
	 */
	public function username()
	{
        if ($this->username !== null) return $this->username;

        if (isset($_SERVER['PHP_AUTH_USER'])) {
        	return $this->username = $_SERVER['PHP_AUTH_USER'];
        }

        if ($this->field()) {
        	if (0 === stripos($this->field, 'basic ')) {
                // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                $exploded = explode(':', base64_decode(substr($this->field, 6)), 2);
                if (count($exploded) == 2) {
                    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = $exploded;
                    return $this->username = $_SERVER['PHP_AUTH_PW'];
                }
            }
        }

        return $this->username = false;
	}

	/**
	 * Get authorization password.
	 *
	 * @var string
	 */
	public function password()
	{
        if ($this->password !== null) $this->password;
        
        if (isset($_SERVER['PHP_AUTH_USER'])) {
        	return $this->password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        }

        if ($this->field()) {
        	if (0 === stripos($this->field, 'basic ')) {
                // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                $exploded = explode(':', base64_decode(substr($this->field, 6)), 2);
                if (count($exploded) == 2) {
                    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = $exploded;
                    return $this->password = $_SERVER['PHP_AUTH_PW'];
                }
            }
        }
     
        return $this->password = false;
	}

	/**
	 * Get authorization token.
	 *
	 * @var string
	 */
	public function token()
	{
        if ($this->token !== null) return $this->token;
        if ($this->field() === false || count($string = explode(' ', $this->field)) != 2) return $this->token = false;
        return $this->token = $string[1];
	}

    /**
     * Get authorization header-field.
     *
     * @return  string
     */
    public function field()
    {
        if ($this->field !== null) return $this->field;

        // HTTP_AUTHORIZATION
    	if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
	        if (empty($_SERVER['PHP_AUTH_DIGEST']) && (0 === stripos($_SERVER['HTTP_AUTHORIZATION'], 'digest '))) {
	            // In some circumstances PHP_AUTH_DIGEST needs to be set.
	            $_SERVER['PHP_AUTH_DIGEST'] = $_SERVER['HTTP_AUTHORIZATION'];
	        }
            return $this->field = $_SERVER['HTTP_AUTHORIZATION'];
        }
        // REDIRECT_HTTP_AUTHORIZATION
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
	        if (empty($_SERVER['PHP_AUTH_DIGEST']) && (0 === stripos($_SERVER['HTTP_AUTHORIZATION'], 'digest '))) {
	            // In some circumstances PHP_AUTH_DIGEST needs to be set.
	            $_SERVER['PHP_AUTH_DIGEST'] = $_SERVER['HTTP_AUTHORIZATION'];
	        }
        	return $this->field = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        // PHP_AUTH_USER
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return $this->field = 'Basic '.base64_encode($this->username().':'.$this->password());
        }
        // PHP_AUTH_DIGEST
        if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            return $this->field = $_SERVER['PHP_AUTH_DIGEST'];
        }

        return $this->field = false;
    }
}