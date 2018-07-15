<?php
require_once(__DIR__ . "/SplClassLoader.php");

$libRoot = dirname(__DIR__);
require_once($libRoot . "/vendor/autoload.php");

$classLoader = new SplClassLoader("Mebius", $libRoot);
$classLoader->register();

if (!class_exists("\PHPUnit\Framework\TestCase")) {
	require_once(dirname(__DIR__) . "/vendor/autoload.php");
}
