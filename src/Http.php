<?php

namespace Raptor\Request;

class Http
{
    /**
     * Query string parameters ($_GET).
     *
     * @var array
     */
	protected $query;

    /**
     * Request body parameters ($_POST).
     *
     * @var array
     */
	protected $params;

    /**
     * Uploaded files ($_FILES).
     *
     * @var array
     */
    protected $files;

    /**
     * Cookies ($_COOKIE).
     *
     * @var array
     */
    protected $cookies;

    /**
     * Session (taken from the $_SESSION).
     *
     * @var array
     */
    protected $session;

    /**
     * Headers (taken from the $_SERVER).
     *
     * @var array
     */
    protected $headers;

    /**
     * Requesting IP.
     *
     * @var string
     */
    protected $ip;

    /**
     * Requesting client application.
     *
     * @var string
     */
    protected $client;

    /**
     * Accepted locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * Accepted character set.
     *
     * @var string
     */
    protected $charset;

    /**
     * Accepted format.
     *
     * @var string
     */
    protected $format;
    
    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $root;
    
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $method;

    /**
     * Initialize the request.
     */
    public function init()
    {
        $this->queries();
        $this->params();
        $this->files();
        $this->cookies();
        $this->sessions();
        $this->headers();
        $this->ip();
        $this->client();
        $this->locale();
        $this->charset();
        $this->format();
        $this->scheme();
        $this->port();
        $this->host();
        $this->prefix();
        $this->root();
        $this->uri();
        $this->method();
    }

    /**
     * Get query string parameters.
     *
     * @return  array
     */
    public function queries()
    {
        if ($this->query === null) return $this->query = $_GET;
        return $this->query;
    }

    /**
     * Get required query string parameter.
     *
     * @return  mixed
     */
    public function query($key)
    {
        if ($this->query === null) $this->queries();
        return isset($this->query[$key]) ? $this->query[$key] : null;
    }

    /**
     * Get request body parameters.
     *
     * @return  array
     */
    public function params()
    {
        if ($this->params === null) {
            if (
                isset($_SERVER['REQUEST_METHOD']) && 
                $_SERVER['REQUEST_METHOD'] === 'POST' && 
                !empty($_POST['_method'])
            ) {
                return $this->params = $_POST;
                unset($this->params['_method']);
            } elseif (
                isset($_SERVER['CONTENT_TYPE']) && 
                $_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded'
            ) parse_str(file_get_contents("php://input"), $this->params);
                // var_dump(file_get_contents("php://input")); die();
            else $this->params = $_POST;
        }
        return $this->params;
    }

    /**
     * Get required request body parameter.
     *
     * @return  mixed
     */
    public function param($key)
    {
        if ($this->params === null) $this->params();
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /**
     * Get uploaded files.
     *
     * @return  array
     */
    public function files()
    {
        if ($this->files === null) return $this->files = $_FILES;
        return $this->files;
    }

    /**
     * Get required uploaded file.
     *
     * @return  array
     */
    public function file($key)
    {
        if ($this->files === null) $this->files();
        return isset($this->files[$key]) ? $this->files[$key] : null;
    }

    /**
     * Get cookies.
     *
     * @return  array
     */
    public function cookies()
    {
        if ($this->cookies === null) return $this->cookies = $_COOKIE;
        return $this->cookies;
    }

    /**
     * Get required cookie.
     *
     * @return  array
     */
    public function cookie($key)
    {
        if ($this->cookies === null) $this->cookies();
        return isset($this->cookies[$key]) ? $this->cookies[$key] : null;
    }

    /**
     * Get sessions.
     *
     * @return  array
     */
    public function sessions()
    {
        if ($this->session === null) {
            if (!session_id()) {
                session_start();
                $this->session = $_SESSION;
            } else {
                $this->session = [];
            }
        }
        return $this->session;
    }

    /**
     * Get required session.
     *
     * @return  array
     */
    public function session($key)
    {
        if ($this->session === null) $this->sessions();
        return isset($this->session[$key]) ? $this->session[$key] : null;
    }

    /**
     * Get request headers.
     *
     * @return  array
     */
    public function headers()
    {
        if ($this->headers === null) {
            $headers = [];
            $contentHeaders = ['CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true];
            foreach ($_SERVER as $key => $value) {
                if (0 === strpos($key, 'HTTP_')) {
                    $headers[$key] = $value;
                }
                // CONTENT_* are not prefixed with HTTP_
                elseif (isset($contentHeaders[$key])) {
                    $headers[$key] = $value;
                }
            }

            if (isset($_SERVER['PHP_AUTH_USER'])) {
                $headers['PHP_AUTH_USER'] = $_SERVER['PHP_AUTH_USER'];
                $headers['PHP_AUTH_PW'] = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            } else {
                /*
                 * php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
                 * For this workaround to work, add these lines to your .htaccess file:
                 * RewriteCond %{HTTP:Authorization} ^(.+)$
                 * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
                 *
                 * A sample .htaccess file:
                 * RewriteEngine On
                 * RewriteCond %{HTTP:Authorization} ^(.+)$
                 * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
                 * RewriteCond %{REQUEST_FILENAME} !-f
                 * RewriteRule ^(.*)$ app.php [QSA,L]
                 */

                $authorizationHeader = null;
                if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                    $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
                } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                    $authorizationHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
                }

                if (null !== $authorizationHeader) {
                    if (0 === stripos($authorizationHeader, 'basic ')) {
                        // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                        $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)), 2);
                        if (count($exploded) == 2) {
                            list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
                        }
                    } elseif (empty($_SERVER['PHP_AUTH_DIGEST']) && (0 === stripos($authorizationHeader, 'digest '))) {
                        // In some circumstances PHP_AUTH_DIGEST needs to be set
                        $headers['PHP_AUTH_DIGEST'] = $authorizationHeader;
                        $_SERVER['PHP_AUTH_DIGEST'] = $authorizationHeader;
                    } elseif (0 === stripos($authorizationHeader, 'bearer ')) {
                        /*
                         * XXX: Since there is no PHP_AUTH_BEARER in PHP predefined variables,
                         *      I'll just set $headers['AUTHORIZATION'] here.
                         *      http://php.net/manual/en/reserved.variables.server.php
                         */
                        $headers['AUTHORIZATION'] = $authorizationHeader;
                    }
                }
            }

            if (isset($headers['AUTHORIZATION'])) {
                $this->headers = $headers;
            } else {
                // PHP_AUTH_USER/PHP_AUTH_PW
                if (isset($headers['PHP_AUTH_USER'])) {
                    $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
                } elseif (isset($headers['PHP_AUTH_DIGEST'])) {
                    $headers['AUTHORIZATION'] = $headers['PHP_AUTH_DIGEST'];
                }
                $this->headers = $headers;
            }
        }
        return $this->headers;
    }

    /**
     * Get required request header.
     *
     * @return  array
     */
    public function header($key)
    {
        if ($this->headers === null) $this->headers();
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    /**
     * Get the IP.
     *
     * @return string
     */
    public function ip()
    {
        if ($this->ip) return $this->ip;
        return $this->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * Get the requesting client.
     *
     * @return string
     */
    public function client()
    {
        if ($this->client) return $this->client;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') || strpos($_SERVER['HTTP_USER_AGENT'], 'OPR/')) return $this->client = 'opera';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge')) return $this->client ='edge';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) return $this->client ='chrome';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) return $this->client ='safari';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) return $this->client ='firefox';
            elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7')) return $this->client ='ie';
        }
        return $this->client = null;
    }

    /**
     * Get the accepted locale.
     *
     * @return string
     */
    public function locale()
    {
        if ($this->locale) return $this->locale;
        return $this->locale = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
    }

    /**
     * Get the accepted charset.
     *
     * @return string
     */
    public function charset()
    {
        if ($this->charset) return $this->charset;
        return $this->charset = isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : null;
    }

    /**
     * Get the accepted fromat.
     *
     * @return string
     */
    public function format()
    {
        if ($this->format) return $this->format;
        return $this->format = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
    }

    /**
     * Gets the URI scheme.
     *
     * @return string
     */
    public function scheme()
    {
        if ($this->scheme) return $this->scheme;
        return $this->scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
    }

    /**
     * Returns the HTTP host being requested.
     *
     * This method can read the client host name from the "X-Forwarded-Host" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Host" header must contain the client host name.
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Host",
     * configure it via "setTrustedHeaderName()" with the "client-host" key.
     *
     * @param  boolean $port If true the port name will be appended to the host if it's non-standard.
     * @return string
     *
     * @throws \UnexpectedValueException when the host name is invalid
     */
    public function host()
    {
        if ($this->host === null) {
            if (isset($_SERVER['HTTP_HOST']) && !$this->host = $_SERVER['HTTP_HOST']) {
                if (!$this->host = $_SERVER['SERVER_NAME']) {
                    $this->host = $_SERVER['SERVER_ADDR'];
                }
            }

            // trim and remove port number from host
            // host is lowercase as per RFC 952/2181
            $this->host = strtolower(preg_replace('/:\d+$/', '', trim($this->host)));

            // as the host can come from the user (HTTP_HOST and depending on the configuration, SERVER_NAME too can come from the user)
            // check that it does not contain forbidden characters (see RFC 952 and RFC 2181)
            // use preg_replace() instead of preg_match() to prevent DoS attacks with long host names
            if ($this->host && '' !== preg_replace('/(?:^\[)?[a-zA-Z0-9-:\]_]+\.?/', '', $this->host)) {
                throw new \Exception(sprintf('Invalid Host "%s"', $this->host));
            }
        }

        $scheme = $this->scheme();
        $port = $this->port();

        if (('http' == $scheme && $port == 80) || ('https' == $scheme && $port == 443)) {
            return $this->host;
        }

        return $this->host.':'.$port;
    }

    /**
     * Returns the port on which the request is made.
     *
     * This method can read the client port from the "X-Forwarded-Port" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Port" header must contain the client port.
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Port",
     * configure it via "setTrustedHeaderName()" with the "client-port" key.
     *
     * @return string
     */
    public function port()
    {
        if ($this->port) return $this->port;
        if (!$host = $_SERVER['HTTP_HOST']) {
            return $this->port = $_SERVER['SERVER_PORT'];
        }

        if ($host[0] === '[') {
            $pos = strpos($host, ':', strrpos($host, ']'));
        } else {
            $pos = strrpos($host, ':');
        }

        if (false !== $pos) {
            return $this->port = (int) substr($host, $pos + 1);
        }

        return $this->port = ('https' === $this->scheme()) ? 443 : 80;
    }

    /**
     * Get the URI prefix.
     *
     * @return string
     */
    public function prefix()
    {
        if ($this->prefix) return $this->prefix;
        $filename = basename($_SERVER['SCRIPT_FILENAME']);

        if (basename($_SERVER['SCRIPT_NAME']) === $filename) {
            $this->prefix = $_SERVER['SCRIPT_NAME'];
        } elseif (basename($_SERVER['PHP_SELF']) === $filename) {
            $this->prefix = $_SERVER['PHP_SELF'];
        } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) {
            $this->prefix = $_SERVER['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility
        } else {
            // Backtrack up the script_filename to find the portion matching
            // php_self
            $segs = explode('/', trim($_SERVER['SCRIPT_FILENAME'], '/'));
            $segs = array_reverse($segs);
            $index = 0;
            $last = count($segs);
            $this->prefix = '';
            do {
                $seg = $segs[$index];
                $this->prefix = '/'.$seg.$this->prefix;
                ++$index;
            } while ($last > $index && (false !== $pos = strpos($_SERVER['PHP_SELF'], $this->prefix)) && 0 != $pos);
        }
        return $this->prefix = trim(str_replace($filename, '', $this->prefix), '/');
    }

    /**
     * Get the URI root.
     *
     * @return string
     */
    public function root()
    {
        if ($this->root) return $this->root;
        return $this->root = $this->scheme().'://'.$this->host(true).'/'.$this->prefix();
    }

    /**
     * Get the URI uri.
     *
     * @return string
     */
    public function uri()
    {
        if ($this->uri) return $this->uri;
        $this->uri = $this->prepareRequestUri();
        $prefix = $this->prefix();
        if ($prefix !== '' && strpos(trim($this->uri,'/'), $prefix) === 0) {
            $this->uri = substr($this->uri, strlen($prefix) + 1);
        }
        return $this->uri = str_replace('//', '', explode('?', $this->uri)[0]);
    }

    /*
     * The following methods are derived from code of the Zend Framework (1.10dev - 2010-01-24)
     *
     * Code subject to the new BSD license (http://framework.zend.com/license/new-bsd).
     *
     * Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
     */

    protected function prepareRequestUri()
    {
        $requestUri = '';

        if (!empty($_SERVER['HTTP_X_ORIGINAL_URL'])) {
            // IIS with Microsoft Rewrite Module
            $requestUri = $_SERVER['HTTP_X_ORIGINAL_URL'];
            unset($_SERVER['HTTP_X_ORIGINAL_URL']);
            unset($_SERVER['UNENCODED_URL']);
            unset($_SERVER['IIS_WasUrlRewritten']);
        } elseif (!empty($_SERVER['HTTP_X_REWRITE_URL'])) {
            // IIS with ISAPI_Rewrite
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            unset($_SERVER['HTTP_X_REWRITE_URL']);
        } elseif (!empty($_SERVER['IIS_WasUrlRewritten']) && $_SERVER['IIS_WasUrlRewritten'] == '1' && $_SERVER['UNENCODED_URL'] != '') {
            // IIS7 with URL Rewrite: make sure we get the unencoded URL (double slash problem)
            $requestUri = $_SERVER['UNENCODED_URL'];
            unset($_SERVER['UNENCODED_URL']);
            unset($_SERVER['IIS_WasUrlRewritten']);
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            // HTTP proxy reqs setup request URI with scheme and host [and port] + the URL uri, only use URL uri
            $schemeAndHttpHost = $this->scheme().'://'.$this->host();
            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }
        } elseif (!empty($_SERVER['ORIG_PATH_INFO'])) {
            // IIS 5.0, PHP as CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if ('' != $_SERVER['QUERY_STRING']) {
                $requestUri .= '?'.$_SERVER['QUERY_STRING'];
            }
            unset($_SERVER['ORIG_PATH_INFO']);
        }

        // normalize the request URI to ease creating sub-requests from this request
        $_SERVER['REQUEST_URI'] = $requestUri;

        return $requestUri;
    }

    /**
     * Get request method.
     *
     * @return  string
     */
    public function method()
    {
        if ($this->method) return $this->method;
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method === 'POST') {
            if (!empty($_SERVER['X-HTTP-METHOD-OVERRIDE'])) {
                $this->method = strtoupper($_SERVER['X-HTTP-METHOD-OVERRIDE']);
            } elseif (!empty($_POST['_method'])) {
                $this->method = strtoupper($_POST['_method']);
            }
        }
        return $this->method;
    }

    /**
     * Returns true if the request is a XMLHttpRequest.
     *
     * It works if your JavaScript library sets an X-Requested-With HTTP header.
     * It is known to work with common JavaScript frameworks:
     *
     * @see http://en.wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript
     *
     * @return bool true if the request is an XMLHttpRequest, false otherwise
     */
    public function ajax()
    {
        return 'XMLHttpRequest' == $_SERVER['X-Requested-With'];
    }
}