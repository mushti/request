[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
### Introduction
Raptor Request is a light weight class to wrap the incoming HTTP request in one single object.
### Installation
To install, just run the following composer command.
```
composer require phpraptor\request
```
### The Request Object
To instantiate the Raptor Request, use the following code.
```php
use Raptor\Request\Http;

$request = new Http;
```
### Request Method
To get the incoming request method or the server verb, call the `method()` method.
```php
$request->method();

// Example output:
'POST'
```
### Query String Parameters
To get all the query string parameters ($_GET), call the `queries()` method.
```php
$request->queries();

// Example output:
[
    'search' => 'john doe',
    'page' => '2'
]
```
If you want get a particular query string parameter, call the `query()` method and pass the required key as the argument.
```php
$request->query('search');

// Example output:
'john doe'
```
### Request Body Parameters
To get all the request body parameters ($_POST), call the `params()` method.
```php
$request->params();

// Example output:
[
    'username' => 'phpraptor',
    'password' => 'johndoe'
]
```
If you want get a particular request body parameter, call the `param()` method and pass the required key as the argument.
```php
$request->param('username');

// Example output:
'phpraptor'
```
### Cookies
To get all the cookies ($_COOKIE), call the `cookies()` method.
```php
$request->cookies();

// Example output:
[
    'PHPSESSID' => 'lopaavhboml1ua6a539b8u0rm7',
    'FONTSIZE' => 'large'
]
```
If you want get a particular cookie, call the `cookie()` method and pass the required key as the argument.
```php
$request->cookie('FONTSIZE');

// Example output:
'large'
```
### Uploaded Files
To get all the uploaded files ($_FILES), call the `files()` method.
```php
$request->files();

// Example output:
[
    'image' => [
        'name' => 'image01.jpg',
        'type' => 'image/jpeg',
        'tmp_name' => '\path\to\tmp\php2660.tmp',
        'error' => 0,
        'size' => 135069
    ]
]
```
If you want get a particular uploaded file, call the `file()` method and pass the required key as the argument.
```php
$request->file('image');

// Example output:
[
    'name' => 'image01.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => '\path\to\tmp\php2660.tmp',
    'error' => 0,
    'size' => 135069
]
```
### Session
To get all the session values ($_SESSION), call the `sessions()` method.
```php
$request->sessions();

// Example output:
[
    'USERID' => 170,
    'FLASHMSG' => [
        'success' => 'Record was updated successfully.'
    ]
]
```
If you want get a particular session value, call the `session()` method and pass the required key as the argument.
```php
$request->session('USERID');

// Example output:
170
```
### Request Headers
To get all the request headers, call the `headers()` method.
```php
$request->headers();

// Example output:
[
    'HTTP_HOST' => 'localhost',
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0',
    'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.5',
    'HTTP_ACCEPT_ENCODING' => string 'gzip, deflate',
    'HTTP_COOKIE' => 'PHPSESSID=t8ih495a8fap4agrkk9ectn2r5',
    'HTTP_CONNECTION' => 'keep-alive'
]
```
If you want get a particular request header, call the `header()` method and pass the required key as the argument.
```php
$request->header('HTTP_HOST');

// Example output:
'localhost'
```
### Request URI
To get the request URI, call the `uri()` method.
```php
$request->uri();

// Example output:
'/company/about'
```
### Scheme
To get the protocol being used by the request, call the `scheme()` method.
```php
$request->scheme();

// Example output:
'http'
```
Supported schemes include:
- `http` for non-encrypted request
- `https` for encrypted request
### Host
To get the HTTP host being requested, call the `host()` method.
```php
$request->host();

// Example output:
'localhost'
```
### Port
To get the port on which the request is made, call the `port()` method.
```php
$request->port();

// Example output:
80
```
### IP Address
To get the IP address from where the request was made, call the `ip()` method.
```php
$request->ip();

// Example output:
'::1'
```
### Requesting Client
To get the requesting client or the browser, call the `client()` method.
```php
$request->client();

// Example output:
'firefox'
```
Supported clients include:
- `firefox` for Mozilla Firefox
- `opera` for Opera
- `edge` for Microsoft Edge
- `chrome` for Google Chrome
- `safari` for Safari
- `ie` for Microsoft Internet Explorer
### Response Format
To get the required response body format supported by the client, call the `format()` method.
```php
$request->format();

// Example output:
'application/json'
```
### Language
To get the language supported by the client, call the `locale()` method.
```php
$request->locale();

// Example output:
'en-US,en;q=0.8'
```
### Character Set
To get the response body character set supported by the client, call the `charset()` method.
```php
$request->charset();

// Example output:
'utf8'
```
### License
This package is released under the [MIT License](https://github.com/phpraptor/request/blob/master/LICENSE).