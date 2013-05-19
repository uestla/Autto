<?php

namespace Autto\Utils;

use Autto;


class Helpers
{

	const S_STATE_SEP = ',';



	/**
	 * @param  Autto\StateSet $set
	 * @return Autto\State
	 */
	static function joinStates(Autto\StateSet $set)
	{
		$names = array();
		foreach ($set as $state) {
			$names[] = $state->getName();
		}

		sort($names);
		return new Autto\State('{' . implode(static::S_STATE_SEP, $names) . '}');
	}

}
