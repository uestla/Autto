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


class TransitionSet extends Set
{

	function __construct()
	{
		parent::__construct('Autto\Transition');
	}



	/**
	 * @param  State $state
	 * @return TransitionSet
	 */
	function filterByState(State $state)
	{
		return $this->filter(function (Transition $transition) use ($state) {
			return $transition->getFrom() === $state;
		});
	}



	/**
	 * @param  Symbol $symbol
	 * @return TransitionSet
	 */
	function filterBySymbol(Symbol $symbol)
	{
		return $this->filter(function (Transition $transition) use ($symbol) {
			return $transition->getOn() === $symbol;
		});
	}



	/**
	 * @param  \Closure $filter
	 * @return TransitionSet
	 */
	private function filter(\Closure $filter)
	{
		$set = new TransitionSet;
		foreach ($this as $transition) {
			if ($filter($transition)) {
				$set->add($transition);
			}
		}

		$set->lock();
		return $set;
	}

}
