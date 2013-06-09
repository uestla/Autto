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
		parent::__construct('Autto\Components\States\StateSet', $items);
	}



	/**
	 * @param  Components\States\StateSet $set
	 * @return bool
	 */
	function has($set)
	{
		$self = clone $this;
		foreach ($self as $stateSet) {
			if ($set->isEqualTo($stateSet)) {
				return TRUE;
			}
		}

		return parent::has($set);
	}

}
