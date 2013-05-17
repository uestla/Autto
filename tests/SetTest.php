<?php


class SetItem
{}


class SetTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Iterating through set while dynamically adding new items
	 */
	function testIteration()
	{
		$set = new Autto\Set('SetItem');
		$set->add(new SetItem);

		$counter = 0;
		foreach ($set as $item) {
			if (++$counter < 10) {
				$set->add(new SetItem);
			}
		}

		$this->assertEquals(10, $counter);
	}

}
