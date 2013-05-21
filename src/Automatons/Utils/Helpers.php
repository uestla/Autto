<?php

namespace Autto\Utils;

use Autto\Components\States\State;
use Autto\Components\States\StateSet;


class Helpers
{

	const S_STATE_SEP = ',';



	/**
	 * @param  StateSet $set
	 * @return State
	 */
	static function joinStates(StateSet $set)
	{
		$names = array();
		foreach ($set as $state) {
			$names[] = $state->getName();
		}

		sort($names);
		return new State('{' . implode(static::S_STATE_SEP, $names) . '}');
	}

}
