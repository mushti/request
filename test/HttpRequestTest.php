<?php
 
use Raptor\Request\Http;
use PHPUnit\Framework\TestCase;
 
class HttpRequestTest extends TestCase
{
	protected $request;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
		// Initialize variables.
		$_GET['search'] = 'John Doe';
		$_SERVER['HTTP_TEST_HEADER'] = true;
		$_COOKIE['PHPSESSID'] = 'lopaavhboml1ua6a539b8u0rm7';
		$_POST['email'] = 'example@example.com';
		$_FILES = [
			'image' => [
				'name' => 'example.jpg',
				'type' => 'image/jpeg',
				'tmp_name' => 'D:\wamp\tmp\php92.tmp',
				'error' => 0,
				'size' => 135069
  			]
		];
		$_SERVER['CONTENT_LENGTH'] = 135467;
		$_SERVER['CONTENT_TYPE'] = 'multipart/form-data; boundary=----WebKitFormBoundarySMTxQwbotazq4ctK';
		// Create Raptor Request object.
        $this->request = new Http;
    }

	public function testRequestInstantiate()
	{
		$this->assertInstanceOf('Raptor\Request\Http', $this->request);
		$this->assertInstanceOf('Raptor\Request\Components\Line', $this->request->line());
		$this->assertInstanceOf('Raptor\Request\Components\Target', $this->request->line()->target());
		$this->assertInstanceOf('Raptor\Request\Components\Header', $this->request->header());
		$this->assertInstanceOf('Raptor\Request\Components\Authorization', $this->request->header()->authorization());
		$this->assertInstanceOf('Raptor\Request\Components\Body', $this->request->body());
	}

	public function testRequestLine()
	{
		// Test request method.
		$this->assertEquals(true, is_string($this->request->line()->method()) || $this->request->line()->method() === false);
		// Test alias for request method.
		$this->assertEquals(true, is_string($this->request->method()) || $this->request->method() === false);
		// Test query string.
		$this->assertEquals(true, is_array($this->request->line()->target()->query()));
		$this->assertEquals($_GET['search'], $this->request->line()->target()->query('search'));
		$this->assertEquals(null, $this->request->line()->target()->query('page'));
		$this->assertEquals(true, $this->request->line()->target()->query('page', true));
		// Test alias for query string.
		$this->assertEquals(true, is_array($this->request->query()));
		$this->assertEquals($_GET['search'], $this->request->query('search'));
		$this->assertEquals(null, $this->request->query('page'));
		$this->assertEquals(true, $this->request->query('page', true));
		// Test request path.
		$this->assertEquals(true, is_string($this->request->line()->target()->path()) || $this->request->line()->target()->path() === false);
		// Test request version.
		$this->assertEquals(true, is_string($this->request->line()->version()) || $this->request->line()->version() === false);
	}

	public function testRequestHeader()
	{
		// Test header fields.
		$this->assertEquals(true, is_array($this->request->header()->all()));
		$this->assertEquals($_SERVER['HTTP_TEST_HEADER'], $this->request->header()->get('HTTP_TEST_HEADER'));
		$this->assertEquals(null, $this->request->header()->get('HTTP_NULL_HEADER'));
		$this->assertEquals(true, $this->request->header()->get('HTTP_NULL_HEADER', true));
		// Test cookies.
		$this->assertEquals(true, is_array($this->request->header()->cookie()));
		$this->assertEquals($_COOKIE['PHPSESSID'], $this->request->header()->cookie('PHPSESSID'));
		$this->assertEquals(null, $this->request->header()->cookie('NULL_COOKIE'));
		$this->assertEquals(true, $this->request->header()->cookie('NULL_COOKIE', true));
		// Test alias for cookies.
		$this->assertEquals(true, is_array($this->request->cookie()));
		$this->assertEquals($_COOKIE['PHPSESSID'], $this->request->cookie('PHPSESSID'));
		$this->assertEquals(null, $this->request->cookie('NULL_COOKIE'));
		$this->assertEquals(true, $this->request->cookie('NULL_COOKIE', true));
		// Test authorization.
		$this->assertEquals(true, is_string($this->request->header()->authorization()->type()) || $this->request->header()->authorization()->type() === false);
		$this->assertEquals(true, is_string($this->request->header()->authorization()->token()) || $this->request->header()->authorization()->token() === false);
		$this->assertEquals(true, is_string($this->request->header()->authorization()->field()) || $this->request->header()->authorization()->field() === false);
		$this->assertEquals(true, is_string($this->request->header()->authorization()->username()) || $this->request->header()->authorization()->username() === false);
		$this->assertEquals(true, is_string($this->request->header()->authorization()->password()) || $this->request->header()->authorization()->password() === false);
		// Test alias for authorization.
		$this->assertEquals(true, is_string($this->request->auth()->type()) || $this->request->auth()->type() === false);
		$this->assertEquals(true, is_string($this->request->auth()->token()) || $this->request->auth()->token() === false);
		$this->assertEquals(true, is_string($this->request->auth()->field()) || $this->request->auth()->field() === false);
		$this->assertEquals(true, is_string($this->request->auth()->username()) || $this->request->auth()->username() === false);
		$this->assertEquals(true, is_string($this->request->auth()->password()) || $this->request->auth()->password() === false);
	}

	public function testRequestBody()
	{
		// Test body parameters.
		$this->assertEquals(true, is_array($this->request->body()->param()));
		$this->assertEquals($_POST['email'], $this->request->body()->param('email'));
		$this->assertEquals(null, $this->request->body()->param('password'));
		$this->assertEquals(true, $this->request->body()->param('password', true));
		// Test alias for body parameters.
		$this->assertEquals(true, is_array($this->request->param()));
		$this->assertEquals($_POST['email'], $this->request->param('email'));
		$this->assertEquals(null, $this->request->param('password'));
		$this->assertEquals(true, $this->request->param('password', true));
		// Test body files.
		$this->assertEquals(true, is_array($this->request->body()->files()));
		$this->assertEquals(true, is_array($this->request->body()->files('image')));
		// Test alias for body files.
		$this->assertEquals(true, is_array($this->request->uploads()));
		$this->assertEquals(true, is_array($this->request->uploads('image')));
		// Test body content.
		$this->assertEquals($_SERVER['CONTENT_LENGTH'], $this->request->body()->contentLength());
		$this->assertEquals($_SERVER['CONTENT_TYPE'], $this->request->body()->contentType());
	}
}