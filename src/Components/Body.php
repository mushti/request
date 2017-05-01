<?php

namespace Raptor\Request\Components;

class Body
{
    /**
     * Request body parameters ($_POST).
     *
     * @var array
     */
    protected $param;

    /**
     * Uploaded files ($_FILES).
     *
     * @var array
     */
    protected $files;

    /**
     * Content length ($_SERVER['CONTENT_LENGTH']).
     *
     * @var array
     */
    protected $contentLength;

    /**
     * Content type ($_SERVER['CONTENT_TYPE']).
     *
     * @var array
     */
    protected $contentType;

    /**
     * Get request body parameters.
     *
     * @param  string $key (optional)
     * @param  mixed $default (optional)
     * @return  mixed
     */
    public function param($key = null, $default = null)
    {
        if ($this->param === null) {
            $this->param = $_POST;
            if (
                isset($_SERVER['CONTENT_TYPE']) && 
                $_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded'
            ) {
                parse_str(file_get_contents("php://input"), $this->param);
            }
        }
        if ($key === null) {
            return $this->param;
        }
        return isset($this->param[$key]) ? $this->param[$key] : $default;
    }

    /**
     * Get uploaded files.
     *
     * @param  string $key (optional)
     * @return  array
     */
    public function files($key = null)
    {
        if ($this->files === null) {
            $this->files = $_FILES;
        }
        if ($key === null) {
            return $this->files;
        }
        return isset($this->files[$key]) ? $this->files[$key] : null;
    }

    /**
     * Get the content length.
     *
     * @return int
     */
    public function contentLength()
    {
        if ($this->contentLength) {
            return $this->contentLength;
        }
        return $this->contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : null;
    }

    /**
     * Get the content type.
     *
     * @return string
     */
    public function contentType()
    {
        if ($this->contentType) {
            return $this->contentType;
        }
        return $this->contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
    }
}