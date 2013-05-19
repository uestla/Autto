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


class StateSetSet extends Set
{

	/** @param  mixed $items */
	function __construct($items = NULL)
	{
		parent::__construct('Autto\StateSet', $items);
	}



	/**
	 * @param  StateSet $set
	 * @return bool
	 */
	function has($set)
	{
		$count = count($set);
		$self = clone $this;
		foreach ($self as $stateSet) {
			if (count($stateSet) === $count && $set->isSubsetOf($stateSet)) {
				return TRUE;
			}
		}

		return parent::has($set);
	}

}
