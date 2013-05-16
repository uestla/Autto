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


class Alphabet extends Set
{

	/** @var array */
	private $values = array();



	function __construct()
	{
		parent::__construct('Autto\Symbol');
	}



	/**
	 * @param  Symbol $item
	 * @return void
	 */
	function beforeAdd($item)
	{
		parent::beforeAdd($item);

		$value = $item->getValue();
		if (isset($this->values[$value])) {
			throw new E\DuplicateItemException;
		}

		$this->values[$value] = TRUE;
	}

}
