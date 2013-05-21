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

use Autto\Components\States\State;
use Autto\Components\States\StateSet;
use Autto\Components\Alphabet\Symbol;


class Transition
{

	/** @var State */
	private $from;

	/** @var StateSet */
	private $to;

	/** @var Symbol */
	private $on;



	/**
	 * @param  State $from
	 * @param  StateSet $to
	 * @param  Symbol $on
	 */
	function __construct(State $from, StateSet $to, Symbol $on)
	{
		$this->from = $from;
		$this->to = $to;
		$this->on = $on;
	}



	/** @return State */
	function getFrom()
	{
		return $this->from;
	}



	/** @return StateSet */
	function getTo()
	{
		return $this->to;
	}



	/** @return Symbol */
	function getOn()
	{
		return $this->on;
	}

}
