<?php


class TransitionSetTest extends PHPUnit_Framework_TestCase
{

	function testFiltering()
	{
		$transitions = new Autto\TransitionSet;

		$a = new Autto\Symbol('a');
		$b = new Autto\Symbol('b');
		$c = new Autto\Symbol('c');

		$q0 = new Autto\State('q0');
		$q1 = new Autto\State('q1');
		$q2 = new Autto\State('q2');
		$q3 = new Autto\State('q3');
		$q4 = new Autto\State('q4');

		$tg0 = new Autto\StateSet;
		$tg0->add($q0)->add($q1);

		$tg1 = new Autto\StateSet;
		$tg1->add($q1);

		$tg2 = new Autto\StateSet;
		$tg2->add($q2);

		$tg3 = new Autto\StateSet;
		$tg3->add($q3);

		$t1 = new Autto\Transition($q0, $tg0, $a);
		$t2 = new Autto\Transition($q3, $tg1, $b);
		$t3 = new Autto\Transition($q2, $tg2, $c);
		$t4 = new Autto\Transition($q3, $tg3, $a);

		$transitions->add($t1)->add($t2)->add($t3)->add($t4);

		$this->assertEquals(2, count($transitions->filterByState($q3)));
		$this->assertEquals(1, count($transitions->filterByState($q2)));
		$this->assertEquals(0, count($transitions->filterByState($q1)));
	}

}
