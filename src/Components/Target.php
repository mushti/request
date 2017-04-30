<?php

namespace Raptor\Request\Components;

class Target
{
	/**
	 * Format of the request-target (see https://tools.ietf.org/html/rfc7230#section-5.3)
	 * Currently Raptor Request supports `origin-form` format only.
	 *
	 * @var string
	 */
	protected $fromat = 'origin-form';

	/**
	 * absolute-path (see https://tools.ietf.org/html/rfc7230#section-5.3.1)
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Query string (see https://tools.ietf.org/html/rfc7230#section-5.3.1)
	 *
	 * @var array
	 */
	protected $query;

	/**
	 * Get format of the request-target.
	 *
	 * @return string
	 */
	public function format()
	{
		if ($this->format !== null) return $this->format;

		if ($this->method() !== 'CONNECT' && $this->method !== 'OPTIONS') {
			if (strpos($this->path(), '/') === 0) {
				return $this->format = 'origin-form';
			}
			return $this->format = 'absolute-form';
		}

		if ($this->method() === 'CONNECT') {
			return $this->format = 'authority-form';
		}

		if ($this->method() === 'OPTIONS') {
			return $this->format = 'asterisk-form';
		}

		return $this->format = false;
	}

	/**
	 * Get the absolute-path.
	 *
	 * @return string
	 */
	public function path()
	{
		if ($this->path !== null) return $this->path;
        if (!empty($_SERVER['HTTP_X_ORIGINAL_URL'])) {
            // IIS with Microsoft Rewrite Module
            unset($_SERVER['HTTP_X_ORIGINAL_URL']);
            unset($_SERVER['UNENCODED_URL']);
            unset($_SERVER['IIS_WasUrlRewritten']);
			return $this->path = explode('?', $_SERVER['HTTP_X_ORIGINAL_URL'])[0];
		}
		if (!empty($_SERVER['HTTP_X_REWRITE_URL'])) {
            // IIS with ISAPI_Rewrite
            unset($_SERVER['HTTP_X_REWRITE_URL']);
            return $this->path = explode('?', $_SERVER['HTTP_X_REWRITE_URL'])[0];
        }
        if (!empty($_SERVER['IIS_WasUrlRewritten']) && $_SERVER['IIS_WasUrlRewritten'] == '1' && $_SERVER['UNENCODED_URL'] != '') {
            // IIS7 with URL Rewrite: make sure we get the unencoded URL (double slash problem)
            unset($_SERVER['UNENCODED_URL']);
            unset($_SERVER['IIS_WasUrlRewritten']);
            return $this->path = explode('?', $_SERVER['UNENCODED_URL'])[0];
        }
        if (!empty($_SERVER['REQUEST_URI'])) {
            return $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
            // HTTP proxy reqs setup request URI with scheme and host [and port] + the URL uri, only use URL uri
            // $schemeAndHttpHost = $this->scheme().'://'.$this->host();
            // if (strpos($requestUri, $schemeAndHttpHost) === 0) {
            //     $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            // }
        }
        if (!empty($_SERVER['ORIG_PATH_INFO'])) {
            // IIS 5.0, PHP as CGI
            // if ('' != $_SERVER['QUERY_STRING']) {
            //     $this->path .= '?'.$_SERVER['QUERY_STRING'];
            // }
            unset($_SERVER['ORIG_PATH_INFO']);
            return $this->path = $_SERVER['ORIG_PATH_INFO'];
        }
		
		return $this->path = false;
	}

	/**
	 * Get the query string.
	 *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
	 */
	public function query($key = null, $default = null)
    {
        if ($this->query === null) $this->query = $_GET;
        if ($key === null) return $this->query;
        return array_key_exists($key, $this->query) ? $this->query[$key] : $default;
    }
}