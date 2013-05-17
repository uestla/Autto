<?php


class TestingAutomaton extends Autto\Automaton
{

	function __construct()
	{
		$a = new Autto\Symbol('a');
		$b = new Autto\Symbol('b');
		$c = new Autto\Symbol('c');
		$eps = new Autto\Symbol(Autto\Symbol::EPSILON);

		$alphabet = new Autto\Alphabet;
		$alphabet->add($a)->add($b)->add($c)->add($eps);


		$A = new Autto\State('A');
		$B = new Autto\State('B');
		$C = new Autto\State('C');

		$states = new Autto\StateSet;
		$states->add($A)->add($B)->add($C);


		$dAaS = new Autto\StateSet;
		$dAaS->add($B);

		$dAcS = new Autto\StateSet;
		$dAcS->add($A)->add($C);

		$dBaS = new Autto\StateSet;
		$dBaS->add($A);

		$dBbS = new Autto\StateSet;
		$dBbS->add($A);

		$dBcS = new Autto\StateSet;
		$dBcS->add($A)->add($B);

		$dBepsS = new Autto\StateSet;
		$dBepsS->add($A);

		$dCaS = new Autto\StateSet;
		$dCaS->add($C);

		$dCbS = new Autto\StateSet;
		$dCbS->add($C);

		$dCcS = new Autto\StateSet;
		$dCcS->add($C);


		$transitions = new Autto\TransitionSet;

		$transitions->add(new Autto\Transition($A, $dAaS, $a));
		$transitions->add(new Autto\Transition($A, $dAcS, $c));

		$transitions->add(new Autto\Transition($B, $dBaS, $a));
		$transitions->add(new Autto\Transition($B, $dBbS, $b));
		$transitions->add(new Autto\Transition($B, $dBcS, $c));
		$transitions->add(new Autto\Transition($B, $dBepsS, $eps));

		$transitions->add(new Autto\Transition($C, $dCaS, $a));
		$transitions->add(new Autto\Transition($C, $dCbS, $b));
		$transitions->add(new Autto\Transition($C, $dCcS, $c));


		$initials = new Autto\StateSet;
		$initials->add($A);

		$finals = new Autto\StateSet;
		$finals->add($A)->add($C);

		parent::__construct($states, $alphabet, $transitions, $initials, $finals);
	}

}
