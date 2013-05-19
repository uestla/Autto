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



	/** @param  mixed $items */
	function __construct($items = NULL)
	{
		parent::__construct('Autto\State', $items);
	}



	/**
	 * @param  State $item
	 * @return StateSet
	 */
	function add($item)
	{
		parent::add($item);
		$this->names[$item->getName()] = TRUE;
		return $this;
	}



	/**
	 * @param  State $state
	 * @return void
	 * @throws E\DuplicateItemException
	 */
	function beforeAdd($state)
	{
		parent::beforeAdd($state);

		if (isset($this->names[$state->getName()])) {
			throw new E\DuplicateItemException;
		}
	}



	/**
	 * @param  State $state
	 * @return bool
	 */
	function has($state)
	{
		return isset($this->names[$state->getName()]) || parent::has($state);
	}



	/**
	 * @param  string $name
	 * @return State|NULL
	 */
	function getByName($name)
	{
		$self = clone $this;
		foreach ($self as $state) {
			if ($state->getName() === $name) {
				return $state;
			}
		}

		return NULL;
	}

}
