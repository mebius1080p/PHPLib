<?php

$libRoot = dirname(__DIR__);
require_once($libRoot . "/vendor/autoload.php");

$traitDir = __DIR__ . DIRECTORY_SEPARATOR . "Trait";
$traitFiles = glob($traitDir . DIRECTORY_SEPARATOR . "*.php");
foreach ($traitFiles as $file) {
	require_once($file);
}

$dummyDIr = __DIR__ . DIRECTORY_SEPARATOR . "DummyClass";
$dummyClassFiles = glob($dummyDIr . DIRECTORY_SEPARATOR . "*.php");
foreach ($dummyClassFiles as $clsFile) {
	require_once($clsFile);
}
