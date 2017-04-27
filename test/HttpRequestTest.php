<?php
 
use Raptor\Request\Http;
use PHPUnit\Framework\TestCase;
 
class HttpRequestTest extends TestCase
{
	public function testHttpRequest()
	{
		$request = new Http;
		$this->assertEquals(true, in_array($request->scheme(), ['http', 'https', null]));
	}
}