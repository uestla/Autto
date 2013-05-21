<?php

/**
 * This file is part of the Autto library
 *
 * Copyright (c) 2013 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/Autto
 */

namespace Autto\Components\States;

use Autto\Exceptions;


class State
{

	/** @var string */
	private $name;



	/** @param  string $name */
	function __construct($name)
	{
		if (preg_match('#\s#', $name)) {
			throw new Exceptions\InvalidStateNameException('State name must not containt white characters.');
		}

		$this->name = (string) $name;
	}



	/** @return string */
	function getName()
	{
		return $this->name;
	}

}
