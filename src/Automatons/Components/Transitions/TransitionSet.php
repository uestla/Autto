<?php

/**
 * This file is part of the Autto library
 *
 * Copyright (c) 2013 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/Autto
 */

namespace Autto\Components\Transitions;

use Autto\Set;
use Autto\Components\States\State;
use Autto\Components\Alphabet\Symbol;


class TransitionSet extends Set
{

	/** @var TransitionSet[] */
	private static $filters = array();



	/** @param  mixed $items */
	function __construct($items = NULL)
	{
		parent::__construct('Autto\Components\Transitions\Transition', $items);
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
		return self::loadFilter($this, $state, function (Transition $transition) use ($state) {
			return $transition->getFrom() === $state;
		});
	}



	/**
	 * @param  Symbol $symbol
	 * @return TransitionSet
	 */
	function filterBySymbol(Symbol $symbol)
	{
		return self::loadFilter($this, $symbol, function (Transition $transition) use ($symbol) {
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
		$self = clone $this;
		$set = new TransitionSet;
		foreach ($self as $transition) {
			if ($filter($transition)) {
				$set->add($transition);
			}
		}

		$set->lock();
		return $set;
	}



	/**
	 * @param  TransitionSet $set
	 * @param  State|Symbol $arg
	 * @param  \Closure $loader
	 * @return TransitionSet
	 */
	private static function loadFilter(TransitionSet $set, $arg, \Closure $loader)
	{
		$key = md5(spl_object_hash($set) . spl_object_hash($arg));
		if (!$set->isLocked() || !isset(self::$filters[$key])) {
			self::$filters[$key] = $set->filter($loader);
		}

		return self::$filters[$key];
	}

}
