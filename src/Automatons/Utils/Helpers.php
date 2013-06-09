<?php

namespace Autto\Utils;

use Autto;
use Autto\StateGroupSet;
use Autto\Components\States\State;
use Autto\Components\States\StateSet;
use Autto\Components\Transitions\Transition;
use Autto\Components\Transitions\TransitionSet;


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



	/**
	 * @param  StateSet $set
	 * @param  State $new
	 * @return void
	 */
	static function memorySaveAdd(StateSet $set, State & $new)
	{
		if ($set->has($new)) {
			$new = $set->getByName($new->getName());

		} else {
			$set->add($new);
		}
	}



	/**
	 * @param  Autto\Automaton $a
	 * @param  StateGroupSet $current
	 * @return StateGroupSet
	 */
	static function buildGroupTable(Autto\Automaton $a, StateGroupSet & $current = NULL)
	{
		if ($current === NULL) { // first iteration -> build the final and non-final states group table
			$current = static::createInitialGroupState($a);
		}

		$naming = 0;
		$metaGroups = array();

		foreach ($current as $group) {
			$created = array(); // stack of new group transition targets
			foreach ($group->getStates() as $state) {
				$targetSet = $group->getTransitions()->filterByState($state)->toTargetSet();
				$key = array_search($targetSet, $created); // intentionally non-strict searching

				if ($key === FALSE) { // create new one
					$naming++;
					$metaGroups[$naming] = array($state);
					$created[$naming] = $targetSet;

				} else {
					$metaGroups[$key][] = $state;
				}
			}
		}

		$groups = new StateGroupSet;
		foreach ($metaGroups as $name => $states) {
			$groups->add(new Autto\StateGroup($name, new StateSet($states)));
		}

		static::factoryGroupTransitions($a, $groups);
		return $groups;
	}



	/**
	 * @param  Autto\Automaton
	 * @return StateGroupSet
	 */
	static function createInitialGroupState(Autto\Automaton $a)
	{
		$nonfinals = new StateSet;
		$finals = new StateSet;

		foreach ($a->getStates() as $state) {
			if ($a->getFinalStates()->has($state)) {
				$finals->add($state);

			} else {
				$nonfinals->add($state);
			}
		}

		$g1 = new Autto\StateGroup('1', $finals);
		$g2 = new Autto\StateGroup('2', $nonfinals);

		$groups = new Autto\StateGroupSet(array($g1, $g2));
		static::factoryGroupTransitions($a, $groups);

		return new StateGroupSet($groups);
	}



	/**
	 * @param  Autto\Automaton $a
	 * @param  Autto\StateGroupSet $groups
	 * @return void
	 */
	static function factoryGroupTransitions(Autto\Automaton $a, Autto\StateGroupSet $groups)
	{
		$groups->lock();

		foreach ($groups as $group) {
			$transitions = new TransitionSet;
			foreach ($group->getStates() as $state) {
				foreach ($a->getTransitions()->filterByState($state) as $transition) {
					foreach ($transition->getTo() as $target) {
						$transitions->add(new Transition(
							$transition->getFrom(),
							new StateSet(array($groups->getStateGroup($target)->toState())),
							$transition->getOn()
						));
					}
				}
			}

			$group->setTransitions($transitions);
		}
	}

}
