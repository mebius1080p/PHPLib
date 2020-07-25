<?php

require_once(__DIR__ . "/SplClassLoader.php");

$libRoot = dirname(__DIR__);
require_once($libRoot . "/vendor/autoload.php");

$classLoader = new SplClassLoader("Mebius", $libRoot);
$classLoader->register();

$traitDir = __DIR__ . DIRECTORY_SEPARATOR . "Trait";
$traitFiles = glob($traitDir . DIRECTORY_SEPARATOR . "*.php");
foreach ($traitFiles as $file) {
	require_once($file);
}

if (!class_exists("\PHPUnit\Framework\TestCase")) {
	require_once(dirname(__DIR__) . "/vendor/autoload.php");
}
