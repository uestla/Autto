<?php


class AlphabetTest extends PHPUnit_Framework_TestCase
{

	function testDuplication()
	{
		$a = new Autto\Symbol('a');
		$b = new Autto\Symbol('a');

		$alphabet = new Autto\Alphabet;

		try {
			$alphabet->add($a);
			$alphabet->add($a);
			$this->fail();

		} catch (Autto\E\DuplicateItemException $e) {}


		try {
			$alphabet->add($a);
			$alphabet->add($b);
			$this->fail();

		} catch (Autto\E\DuplicateItemException $e) {}
	}

}
