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
	}



	/** @return Automaton */
	function removeEpsilon()
	{
		if ($this->alphabet->hasEpsilon()) {
			$finals = new StateSet;
			$transitions = new TransitionSet;

			foreach ($this->states as $state) {
				$closure = $this->epsilonClosure($state);

				foreach ($this->alphabet as $symbol) {
					if (!$symbol->isEpsilon()) {
						$to = new StateSet;
						foreach ($closure as $s) {
							!$finals->has($s) && $finals->add($s);

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

			$alphabet = new Alphabet;
			foreach ($this->alphabet as $symbol) {
				if (!$symbol->isEpsilon()) {
					$alphabet->add($symbol);
				}
			}

			$this->alphabet = $alphabet;
			$this->transitions = $transitions;
			$this->finals = $finals;
		}

		return $this;
	}



	/**
	 * @param  State $state
	 * @return StateSet
	 */
	function epsilonClosure(State $state)
	{
		$closure = new StateSet;
		$closure->add($state);

		foreach ($closure as $s) {
			foreach ($this->transitions->filterByState($s)->filterByEpsilon() as $transition) {
				foreach ($transition->getTo() as $target) {
					!$closure->has($target) && $closure->add($target);
				}
			}
		}

		return $closure;
	}



	/**
	 * @return void
	 * @throws E\EmptySetException
	 * @throws E\InvalidSetException
	 * @throws E\StateNotFoundException
	 */
	private function validate()
	{
		if (!count($this->alphabet) || !count($this->transitions)
				|| !count($this->states) || !count($this->initials)) {
			throw new E\EmptySetException;
		}

		if (!$this->initials->isSubsetOf($this->states)
				|| !$this->finals->isSubsetOf($this->states)) {
			throw new E\InvalidSetException;
		}

		foreach ($this->transitions as $transition) {
			if (!$this->states->has($transition->getFrom())) {
				throw new E\StateNotFoundException($transition->getFrom());
			}

			if (!$transition->getTo()->isSubsetOf($this->states)) {
				throw new E\InvalidSetException;
			}

			if (!$this->alphabet->has($transition->getOn())) {
				throw new E\SymbolNotFoundException($transition->getOn());
			}
		}
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

}
