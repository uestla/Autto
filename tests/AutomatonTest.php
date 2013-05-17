<?php

use Autto\State;
use Autto\Symbol;
use Autto\StateSet;
use Autto\Alphabet;
use Autto\Automaton;
use Autto\Transition;
use Autto\TransitionSet;
use Autto\E\EmptySetException;
use Autto\E\InvalidSetException;


class AutomatonTest extends PHPUnit_Framework_TestCase
{

	function testCreationFail()
	{
		try {
			new Automaton(new StateSet, new Alphabet, new TransitionSet, new StateSet, new StateSet);

		} catch (EmptySetException $e) {}


		try {
			$a = new Symbol('a');
			$alphabet = new Alphabet;
			$alphabet->add($a);

			$q0 = new State('q0');
			$states = new StateSet;
			$states->add($q0);

			$initials = new StateSet;
			$initials->add(new State('q1'));

			$target = new StateSet;
			$target->add($q0);

			$t = new Transition($q0, $target, $a);
			$transitions = new TransitionSet;
			$transitions->add($t);

			new Automaton($states, $alphabet, $transitions, $initials, new StateSet);
			$this->fail();

		} catch (InvalidSetException $e) {}
	}



	function testEpsilonClosure()
	{
		$automaton = new TestingAutomaton;
		foreach ($automaton->getStates() as $state) {
			if ($state->getName() === 'B') {
				break;
			}
		}

		$this->assertEquals(2, count($automaton->epsilonClosure($state)));
	}



	function testRemoveEpsilon()
	{
		$renderer = new Autto\Renderers\AsciiRenderer;

		$automaton = new TestingAutomaton;
		$renderer->render($automaton);

		$automaton->removeEpsilon();
		$renderer->render($automaton);
	}

}
