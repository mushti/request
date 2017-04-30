[![Build Status](https://travis-ci.org/phpraptor/request.svg?branch=master)](https://travis-ci.org/phpraptor/request)
[![Code Climate](https://codeclimate.com/github/phpraptor/request/badges/gpa.svg)](https://codeclimate.com/github/phpraptor/request)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Introduction
------------
Raptor Request is a light weight tool built using the SOLID pattern to wrap the incoming HTTP request in one single object. The struture of the request object is based on [RFC 7230](https://tools.ietf.org/html/rfc7230) and [RFC 7231](https://tools.ietf.org/html/rfc7231).

Installation
------------
To install, just run the following composer command.
```
composer require phpraptor\request
```

The Request Object
------------------
To capture the request, simply create an object of the `Http` class.
```php
use Raptor\Request\Http;

$request = new Http;
```
### Components
As stated in [Section 3](https://tools.ietf.org/html/rfc7230#section-3) of the [RFC 7230](https://tools.ietf.org/html/rfc7230),
```
All HTTP/1.1 messages consist of a start-line followed by a sequence
of octets in a format similar to the Internet Message Format
[[RFC5322](https://tools.ietf.org/html/rfc5322)]: zero or more header fields (collectively referred to as
the "headers" or the "header section"), an empty line indicating the
end of the header section, and an optional message body.

HTTP-message   = start-line
                 *( header-field CRLF )
                 CRLF
                 [ message-body ]
```
According to [Section 3.1](https://tools.ietf.org/html/rfc7230#section-3.1) of the [RFC 7230](https://tools.ietf.org/html/rfc7230), for a request message the `start-line` will be a `request-line`.
So the request object have three components that you can access by calling `line()`, `header()` and `body()` methods on the request object.
```php
$request->line();    // request-line
$request->header();  // header field
$request->body();    // message body
```

Request-line
------------
[Section 3.1.1](https://tools.ietf.org/html/rfc7230#section-3.1.1) of the [RFC 7230](https://tools.ietf.org/html/rfc7230) states,
```
A request-line begins with a method token, followed by a single space
(SP), the request-target, another single space (SP), the protocol
version, and ends with CRLF.

request-line   = method SP request-target SP HTTP-version CRLF
```
### Method Token (Request Method)
To get the method token or the request method, call the `method()` function.
```php
$request->line()->method();
$request->method();    // short approach

// Example output:
'POST'
```
### Request-target
[Section 5.3](https://tools.ietf.org/html/rfc7230#section-5.3) of the [RFC 7230](https://tools.ietf.org/html/rfc7230) states,
```
There are four distinct formats for the request-target,
depending on both the method being requested and whether the request
is to a proxy.

request-target = origin-form
                 / absolute-form
                 / authority-form
                 / asterisk-form
```
#### Target Path (Request URI)
To get the target path or the request URI, call the `path()` method.
```php
$request->line()->target()->path();

// Example output:
'/company/about'
```
#### Query String Parameters ($_GET)
According to [Section 3.1.1](https://tools.ietf.org/html/rfc7230#section-5.3.1) of the [RFC 7230](https://tools.ietf.org/html/rfc7230), if the format of the `request-target` is `origin-form`, the `request-target` can have query string parameters. To get all the query string parameters, call the `query()` method.
```php
$request->line()->target()->query();
$request->query();    // short approach

// Example output:
[
    'search' => 'john doe',
    'page' => '2'
]
```
If you want to get a particular query string parameter, call the `query()` method and pass the required key as the argument.
```php
$request->line()->target()->query('search');
$request->query('search');    // short approach

// Example output:
'john doe'
```
You can also define a default value to return if a key does not exist in the query string parameters.
```php
$request->line()->target()->query('search', 'default value');
$request->query('search', 'default value');    // short approach

// Example output:
'default value'
```
### HTTP-version
To get the HTTP-version, call the `version()` method.
```php
$request->line()->version();

// Example output:
'HTTP/1.1'
```

Header Fields (Request Headers)
-------------------------------
To get all the header fields or the request headers, call the `all()` method on the `header()` method of the request object.
```php
$request->header()->all();

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
If you want to get a particular header field, call the `get()` method and pass the required key as the argument.
```php
$request->header()->get('HTTP_HOST');

// Example output:
'localhost'
```
You can also define a default value to return if a key does not exist in the header fields.
```php
$request->header()->get('HTTP_EXAMPLE', 'default value');

// Example output:
'default value'
```

#### Cookies ($_COOKIE)
The cookies are sent along with the request headers, so to get the cookies, call the `cookie()` method.
```php
$request->header()->cookie();
$request->cookie();    // short approach

// Example output:
[
    'PHPSESSID' => 'lopaavhboml1ua6a539b8u0rm7'
]
```
If you want to get a cookie, call the `cookie()` method and pass the required key as the argument.
```php
$request->header()->cookie('PHPSESSID');
$request->cookie(PHPSESSID');    // short approach

// Example output:
'lopaavhboml1ua6a539b8u0rm7'
```
You can also define a default value to return if a key does not exist in the cookies.
```php
$request->header()->cookie('FONTSIZE', '14px');
$request->cookie('FONTSIZE', '14px');    // short approach

// Example output:
'14px'
```

Message Body
------------
[Section 3.3](https://tools.ietf.org/html/rfc7230#section-3.3) of the [RFC 7230](https://tools.ietf.org/html/rfc7230) states,
```
The message body (if any) of an HTTP message is used to carry the
payload body of that request
```
The payload body can contain request body parameters i.e. `$_POST` and any uploaded files, i.e. `$_FILES`.

#### Request Body Parameters ($_POST)
To get all the request body parameters, call the `params()` method.
```php
$request->body()->param();
$request->param();    // short approach

// Example output:
[
    'username' => 'phpraptor',
    'password' => 'johndoe'
]
```
If you want get a particular request body parameter, call the `param()` method and pass the required key as the argument.
```php
$request->body()->param('username');
$request->param('username');    // short approach

// Example output:
'phpraptor'
```
You can also define a default value to return if a key does not exist in the request body parameters.
```php
$request->body()->param('category_id', 30);
$request->param('category_id', 30);    // short approach

// Example output:
30
```

#### Uploaded Files ($_FILE)
To get all the uploaded files, call the `files()` method.
```php
$request->body()->files();
$request->files();    // short approach

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
$request->body()->file('image');
$request->file('image');    // short approach

// Example output:
[
    'name' => 'image01.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => '\path\to\tmp\php2660.tmp',
    'error' => 0,
    'size' => 135069
]
```

#### Content Length
To get the content length of the message body, call the `contentLength()` method.
```php
$request->body()->contentLength();

// Example output:
135467
```

#### Content Type
To get the content type of the message body, call the `contentType()` method.
```php
$request->body()->contentType();

// Example output:
'multipart/form-data; boundary=----WebKitFormBoundarySMTxQwbotazq4ctK'
```