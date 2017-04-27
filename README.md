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
$request->session('HTTP_HOST');

// Example output:
'localhost'
```
### Request Client
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
### Client's IP
To get the client's IP address, call the `ip()` method.
```php
$request->ip();

// Example output:
'::1'
```
### License
This package is released under the [MIT License](https://github.com/phpraptor/request/blob/master/LICENSE).