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

// Output
'POST'
```
### Query String Parameters
To get all the query string parameters ($_GET), call the `queries()` method.
```php
$request->queries();

// Output
[
    'search' => 'john doe',
    'page' => '2'
]
```
If you want get a particular query string parameter, call the `query()` method and pass the required key as the argument.
```php
$request->query('search');

// Output
'john doe'
```
### Request Body Parameters
To get all the request body parameters ($_POST), call the `params()` method.
```php
$request->params();

// Output
[
    'username' => 'phpraptor',
    'password' => 'johndoe'
]
```
If you want get a particular request body parameter, call the `param()` method and pass the required key as the argument.
```php
$request->param('username');

// Output
'phpraptor'
```
### Cookies
To get all the cookies ($_COOKIE), call the `cookies()` method.
```php
$request->cookies();

// Output
[
    'PHPSESSID' => 'lopaavhboml1ua6a539b8u0rm7',
    'FONTSIZE' => 'large'
]
```
If you want get a particular cookie, call the `cookie()` method and pass the required key as the argument.
```php
$request->cookie('FONTSIZE');

// Output
'large'
```
### Uploaded Files
To get all the uploaded files ($_FILES), call the `files()` method.
```php
$request->files();

// Output
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

// Output
[
    'name' => 'image01.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => '\path\to\tmp\php2660.tmp',
    'error' => 0,
    'size' => 135069
]
```

### License
This package is released under the [MIT License](https://github.com/phpraptor/request/blob/master/LICENSE).