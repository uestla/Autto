<?php

use Autto\State;
use Autto\StateSet;
use Autto\E\DuplicateItemException;
use Autto\E\InvalidStateNameException;
use Autto\E\UpdatingLockedSetException;


class SetTest extends PHPUnit_Framework_TestCase
{

	function testBasicsSet()
	{
		$set = new StateSet;
		$this->assertEquals(0, count($set));

		$q0 = new State('q0');
		$q1 = new State('q1');

		$set->add($q0);
		$this->assertTrue($set->has($q0));
		$this->assertFalse($set->has($q1));

		$set->add($q1);
		$this->assertTrue($set->has($q1));
	}



	function testDuplication()
	{
		$q = new State('q');
		$set = new StateSet;
		$set->add($q);

		try {
			$set->add($q);
			$this->fail();

		} catch (DuplicateItemException $e) {}


		try {
			$set->add(new State('q'));
			$this->fail();

		} catch (DuplicateItemException $e) {}
	}



	function testLock()
	{
		$set = new StateSet;
		$set->lock();

		try {
			$set->add(new State('q'));

		} catch (UpdatingLockedSetException $e) {}
	}



	function testStateName()
	{
		try {
			new State('-');
			$this->fail();

		} catch (InvalidStateNameException $e) {}


		try {
			new State('_');
			$this->fail();

		} catch (InvalidStateNameException $e) {}


		try {
			new State('č');
			$this->fail();

		} catch (InvalidStateNameException $e) {}
	}

}
