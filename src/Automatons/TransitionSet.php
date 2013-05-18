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

	/** @param  mixed $items */
	function __construct($items = NULL)
	{
		parent::__construct('Autto\Transition', $items);
	}



	/**
	 * @param  Transition $item
	 * @return TransitionSet
	 */
	function add($item)
	{
		parent::add($item);
		$item->getTo()->lock();
		return $this;
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



	/** @return TransitionSet */
	function filterByEpsilon()
	{
		return $this->filter(function (Transition $transition) {
			return $transition->getOn()->getValue() === Symbol::EPSILON;
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
