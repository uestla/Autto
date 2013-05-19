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


class Symbol
{

	/** @var mixed */
	private $value;

	const EPSILON = '';



	/** @param  mixed $value */
	function __construct($value)
	{
		$this->value = $value;
	}



	/** @return bool */
	function isEpsilon()
	{
		return $this->value === static::EPSILON;
	}



	/** @return string */
	function getValue()
	{
		return $this->value;
	}

}
