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


class StateSet extends Set
{

	/** @var array */
	private $names = array();



	function __construct()
	{
		parent::__construct('Autto\State');
	}



	/**
	 * @param  State $state
	 * @return void
	 * @throws E\DuplicateItemException
	 */
	function beforeAdd($state)
	{
		parent::beforeAdd($state);

		$name = $state->getName();
		if (isset($this->names[$name])) {
			throw new E\DuplicateItemException;
		}

		$this->names[$name] = TRUE;
	}



	/**
	 * @param  State $state
	 * @return bool
	 */
	function has($state)
	{
		return isset($this->names[$state->getName()]) || parent::has($state);
	}

}
