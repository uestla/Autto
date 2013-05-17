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

	/** @var bool */
	private $hasEpsilon = FALSE;



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

		if (!$this->hasEpsilon && $item->isEpsilon()) {
			$this->hasEpsilon = TRUE;
		}

		$this->values[$value] = TRUE;
	}



	/**
	 * @param  Symbol $symbol
	 * @return bool
	 */
	function has($symbol)
	{
		return isset($this->values[$symbol->getValue()]) || parent::has($symbol);
	}



	/** @return bool */
	function hasEpsilon()
	{
		return $this->hasEpsilon;
	}

}
