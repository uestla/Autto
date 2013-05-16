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
