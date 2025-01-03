<?php

use PHPUnit\Framework\TestCase;
use Mebius\Net\{CSPManager2, DirectiveItem};
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CSPManager2::class)]
#[CoversClass(DirectiveItem::class)]
class CSPManager2Test extends TestCase
{
	public function testNormal()
	{
		$csp = new CSPManager2();
		$this->assertEquals("Content-Security-Policy: default-src 'self';", $csp->getCSPString());

		$csp->addDirective("hoge", "fuga");
		$this->assertEquals("Content-Security-Policy: default-src 'self';", $csp->getCSPString());

		$csp->addDirective(CSPManager2::IMAGE, "data:");
		$this->assertEquals("Content-Security-Policy: default-src 'self';img-src data:;", $csp->getCSPString());

		$csp->addDirective(CSPManager2::SCRIPT, "https://hoge.example.com");
		$csp->addDirective(CSPManager2::SCRIPT, "https://hoge.example.com");
		$this->assertEquals("Content-Security-Policy: default-src 'self';img-src data:;script-src https://hoge.example.com;", $csp->getCSPString());
	}
}
