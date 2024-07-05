<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/SampleInput.php");

class ValidatorTest extends TestCase
{
	public function testValidatorAllOk()
	{
		$data = new SampleInput();
		$data->tel = "0120-444-444";
		$data->name = "hoge-";
		$data->date = "2024-05-02";
		$data->number = 4;
		$data->regsample = "akeow";
		$data->postal = "123-4567";
		$data->mail1 = "テスト@example.com";
		$data->mail2 = "test@example.com";
		$data->str1 = "fuga";
		$data->str2 = "fuga";
		$data->validate();
		$errors = $data->getValidateError();

		// var_dump($errors);

		$this->assertSame(0, count($errors));
	}
	public function testValidatorAllBAD()
	{
		$data = new SampleInput();
		$data->tel = "hoge";
		$data->name = "";
		$data->date = "2024-13-02";
		$data->number = 6;
		$data->regsample = "akeosdfwew456w";
		$data->postal = "ewfewef";
		$data->mail1 = "wewewf";
		$data->mail2 = "テスト@example.com";
		$data->str1 = "fuga";
		$data->str2 = "moge";
		$data->validate();
		$errors = $data->getValidateError();

		// var_dump($errors);

		$this->assertSame(9, count($errors));
	}
	// public function testEx()
	// {
	// 	//$this->expectException(Exception::class);
	// 	//$this->expectExceptionMessage("xxxxxxxxxxxx");
	// }
}
