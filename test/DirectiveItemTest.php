<?php
use PHPUnit\Framework\TestCase;
use Mebius\Net\{DirectiveItem, CSPManager2};

class DirectiveItemTest extends TestCase
{
	public function testNormal()
	{
		$di = new DirectiveItem(CSPManager2::SCRIPT);
		$di->appendDirective("", "");
		$this->assertEquals(CSPManager2::SCRIPT, $di->getSource());
		$this->assertEquals("", $di->getDirectiveString());

		$di->appendDirective("", "hoge");
		$this->assertEquals("", $di->getDirectiveString());

		$di->appendDirective(CSPManager2::FRAME, DirectiveItem::CSP_SELF);
		$this->assertEquals("", $di->getDirectiveString());

		$di->appendDirective(CSPManager2::SCRIPT, "");
		$this->assertEquals("", $di->getDirectiveString());

		$di->appendDirective(CSPManager2::SCRIPT, "hoge");
		$this->assertEquals("", $di->getDirectiveString());

		$di->appendDirective(CSPManager2::SCRIPT, DirectiveItem::CSP_SELF);
		$this->assertEquals("script-src 'self';", $di->getDirectiveString());

		$di->appendDirective(CSPManager2::SCRIPT, DirectiveItem::CSP_BLOB);
		$this->assertEquals("script-src 'self' blob:;", $di->getDirectiveString());

		//重複
		$di->appendDirective(CSPManager2::SCRIPT, DirectiveItem::CSP_BLOB);
		$this->assertEquals("script-src 'self' blob:;", $di->getDirectiveString());
	}
}
