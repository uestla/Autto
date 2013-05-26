<?php

/**
 * This file is part of the Autto library
 *
 * Copyright (c) 2013 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/Autto
 */

namespace Autto;

use Autto\Components\States\State;
use Autto\Components\States\StateSet;
use Autto\Components\Alphabet\Alphabet;
use Autto\Components\Transitions\Transition;
use Autto\Components\Transitions\TransitionSet;


class Automaton
{

	/** @var StateSet */
	protected $states;

	/** @var Alphabet */
	protected $alphabet;

	/** @var TransitionSet */
	protected $transitions;

	/** @var StateSet */
	protected $initials;

	/** @var StateSet */
	protected $finals;

	/** @var bool */
	protected $deterministic = NULL;



	/**
	 * @param  StateSet $states
	 * @param  Alphabet $alphabet
	 * @param  TransitionSet $transitions
	 * @param  StateSet $initials
	 * @param  StateSet $finals
	 */
	function __construct(StateSet $states, Alphabet $alphabet, TransitionSet $transitions,
			StateSet $initials, StateSet $finals)
	{
		$this->construct($states, $alphabet, $transitions, $initials, $finals);
	}



	/** @return Automaton */
	function removeEpsilon()
	{
		if ($this->alphabet->hasEpsilon()) {
			$alphabet = new Alphabet;
			$transitions = new TransitionSet;
			$finals = new StateSet;

			foreach ($this->states as $state) {
				$closure = $this->epsilonClosure($state);

				foreach ($this->alphabet as $symbol) {
					if (!$symbol->isEpsilon()) {
						!$alphabet->has($symbol) && $alphabet->add($symbol);

						$to = new StateSet;
						foreach ($closure as $s) {
							$this->finals->has($s) && !$finals->has($s) && $finals->add($s);

							foreach ($this->transitions->filterByState($s)->filterBySymbol($symbol) as $t) {
								foreach ($t->getTo() as $target) {
									!$to->has($target) && $to->add($target);
								}
							}
						}

						$transitions->add(new Transition($state, $to, $symbol));
					}
				}
			}

			$this->construct($this->states, $alphabet, $transitions, $this->initials, $finals);
		}

		return $this;
	}



	/**
	 * @param  State $state
	 * @return StateSet
	 */
	function epsilonClosure(State $state)
	{
		$closure = new StateSet(array($state));
		foreach ($closure as $s) {
			foreach ($this->transitions->filterByState($s)->filterByEpsilon() as $transition) {
				foreach ($transition->getTo() as $target) {
					!$closure->has($target) && $closure->add($target);
				}
			}
		}

		return $closure;
	}



	/** @return Automaton */
	function determinize()
	{
		if (!$this->isDeterministic()) {
			$this->removeEpsilon();

			$states = new StateSet;
			$transitions = new TransitionSet;
			$initials = new StateSet;
			$finals = new StateSet;

			$queue = new StateSetSet(array($this->initials));

			foreach ($queue as $set) {
				$new = Utils\Helpers::joinStates($set);
				Utils\Helpers::memorySaveAdd($states, $new);
				!count($initials) && $initials->add($new);

				foreach ($this->alphabet as $symbol) {
					$to = new StateSet;
					foreach ($set as $state) {
						$this->finals->has($state) && !$finals->has($new) && $finals->add($new);

						foreach ($this->transitions->filterByState($state)->filterBySymbol($symbol) as $transition) {
							foreach ($transition->getTo() as $t) {
								!$to->has($t) && $to->add($t);
							}
						}
					}

					$target = Utils\Helpers::joinStates($to);
					Utils\Helpers::memorySaveAdd($states, $target);
					$transitions->add(new Transition($new, new StateSet(array($target)), $symbol));

					!$queue->has($to) && $queue->add($to);
				}
			}

			$this->construct($states, $this->alphabet, $transitions, $initials, $finals);
			$this->deterministic = TRUE;
		}

		return $this;
	}



	/**
	 * @param  StateSet $states
	 * @param  Alphabet $alphabet
	 * @param  TransitionSet $transitions
	 * @param  StateSet $initials
	 * @param  StateSet $finals
	 */
	private function construct(StateSet $states, Alphabet $alphabet, TransitionSet $transitions,
			StateSet $initials, StateSet $finals)
	{
		$states->lock();
		$alphabet->lock();
		$transitions->lock();
		$initials->lock();
		$finals->lock();

		$this->states = $states;
		$this->alphabet = $alphabet;
		$this->transitions = $transitions;
		$this->initials = $initials;
		$this->finals = $finals;

		$this->validate();
		$this->deterministic = NULL;
	}



	/**
	 * @return void
	 * @throws Exceptions\EmptySetException
	 * @throws Exceptions\InvalidSetException
	 * @throws Exceptions\StateNotFoundException
	 */
	private function validate()
	{
		if (!count($this->alphabet) || !count($this->transitions)
				|| !count($this->states) || !count($this->initials)) {
			throw new Exceptions\EmptySetException;
		}

		if (!$this->initials->isSubsetOf($this->states)
				|| !$this->finals->isSubsetOf($this->states)) {
			throw new Exceptions\InvalidSetException;
		}

		foreach ($this->transitions as $transition) {
			if (!$this->states->has($transition->getFrom())) {
				throw new Exceptions\StateNotFoundException($transition->getFrom());
			}

			if (!$transition->getTo()->isSubsetOf($this->states)) {
				throw new Exceptions\InvalidSetException;
			}

			if (!$this->alphabet->has($transition->getOn())) {
				throw new Exceptions\SymbolNotFoundException($transition->getOn());
			}
		}
	}



	/** @return bool */
	private function discoverDeterminism()
	{
		if ($this->alphabet->hasEpsilon() || count($this->initials) > 1) {
			return FALSE;
		}

		$reachable = new StateSet;
		foreach ($this->states as $state) {
			foreach ($this->alphabet as $symbol) {
				foreach ($this->transitions->filterByState($state)->filterBySymbol($symbol) as $transition) {
					$this->initials->has($transition->getFrom())
							&& !$reachable->has($transition->getFrom())
							&& $reachable->add($transition->getFrom());

					if (count($transition->getTo()) !== 1) {
						return FALSE;
					}

					foreach ($transition->getTo() as $target) {
						!$reachable->has($target) && $reachable->add($target);
					}
				}
			}
		}

		return count($reachable) === count($this->states);
	}



	/** @return StateSet */
	function getStates()
	{
		return $this->states;
	}



	/** @return Alphabet */
	function getAlphabet()
	{
		return $this->alphabet;
	}



	/** @return TransitionSet */
	function getTransitions()
	{
		return $this->transitions;
	}



	/** @return StateSet */
	function getInitialStates()
	{
		return $this->initials;
	}



	/** @return StateSet */
	function getFinalStates()
	{
		return $this->finals;
	}



	/** @return bool */
	function isDeterministic()
	{
		if ($this->deterministic === NULL) {
			$this->deterministic = $this->discoverDeterminism();
		}

		return $this->deterministic;
	}

}
