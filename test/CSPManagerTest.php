<?php

use PHPUnit\Framework\TestCase;
use Mebius\net\CSPManager;

class CSPManagerTest extends TestCase
{
	public function testNoAddition()
	{
		$csp = new CSPManager();
		$str = $csp->getCSP();
		$expect = "Content-Security-Policy: default-src 'self';img-src 'self' data: blob:;style-src 'self';script-src 'self';frame-src 'self';child-src 'self';connect-src 'self';";
		$this->assertEquals($expect, $str);
	}
	public function testAddImage()
	{
		$csp = new CSPManager();
		$csp->addImage("https:\\hoge.net");
		$str = $csp->getCSP();
		$expect = "Content-Security-Policy: default-src 'self';img-src 'self' data: blob: https:\\hoge.net;style-src 'self';script-src 'self';frame-src 'self';child-src 'self';connect-src 'self';";
		$this->assertEquals($expect, $str);
	}
	public function testAddStyle()
	{
		$csp = new CSPManager();
		$csp->addStyle("https:\\hoge.net");
		$str = $csp->getCSP();
		$expect = "Content-Security-Policy: default-src 'self';img-src 'self' data: blob:;style-src 'self' https:\\hoge.net;script-src 'self';frame-src 'self';child-src 'self';connect-src 'self';";
		$this->assertEquals($expect, $str);
	}
	public function testAddScript()
	{
		$csp = new CSPManager();
		$csp->addScript("https:\\hoge.net");
		$str = $csp->getCSP();
		$expect = "Content-Security-Policy: default-src 'self';img-src 'self' data: blob:;style-src 'self';script-src 'self' https:\\hoge.net;frame-src 'self';child-src 'self';connect-src 'self';";
		$this->assertEquals($expect, $str);
	}
	public function testAddFrameAndChild()
	{
		$csp = new CSPManager();
		$csp->addChild("https:\\hoge.net");
		$str = $csp->getCSP();
		$expect = "Content-Security-Policy: default-src 'self';img-src 'self' data: blob:;style-src 'self';script-src 'self';frame-src 'self' https:\\hoge.net;child-src 'self' https:\\hoge.net;connect-src 'self';";
		$this->assertEquals($expect, $str);
	}
	public function testAddConnect()
	{
		$csp = new CSPManager();
		$csp->addConnect("https:\\hoge.net");
		$str = $csp->getCSP();
		$expect = "Content-Security-Policy: default-src 'self';img-src 'self' data: blob:;style-src 'self';script-src 'self';frame-src 'self';child-src 'self';connect-src 'self' https:\\hoge.net;";
		$this->assertEquals($expect, $str);
	}
}
