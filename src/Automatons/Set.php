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


class Set implements \Iterator, \Countable
{

	/** @var string */
	private $type;

	/** @var array */
	private $items = array();

	/** @var array */
	private $hashes = array();

	/** @var bool */
	private $locked = FALSE;



	/**
	 * @param  string $type
	 * @param  mixed $items
	 */
	function __construct($type, $items = NULL)
	{
		$this->type = $type;

		if ($items !== NULL) {
			foreach ($items as $item) {
				$this->add($item);
			}
		}
	}



	/**
	 * @param  object $item
	 * @return Set
	 * @throws E\InvalidItemTypeException
	 */
	function add($item)
	{
		$this->beforeAdd($item);
		$this->items[] = $item;
		$this->hashes[spl_object_hash($item)] = TRUE;
		return $this;
	}



	/**
	 * @param  object $item
	 * @return void
	 * @throws E\DuplicateItemException
	 */
	function beforeAdd($item)
	{
		$this->checkLock();

		if (!is_object($item) || !$item instanceof $this->type) {
			throw new E\InvalidItemTypeException;
		}

		if ($this->has($item)) {
			throw new E\DuplicateItemException;
		}
	}



	/**
	 * @param  object $item
	 * @return bool
	 */
	function has($item)
	{
		return isset($this->hashes[spl_object_hash($item)]);
	}



	/**
	 * @param  Set $set
	 * @return bool
	 */
	function isSubsetOf(Set $set)
	{
		$self = clone $this;
		foreach ($self as $item) {
			if (!$set->has($item)) {
				return FALSE;
			}
		}

		return TRUE;
	}



	/** @return void */
	function lock()
	{
		$this->locked = TRUE;
	}



	/** @return bool */
	function isLocked()
	{
		return $this->locked;
	}



	/**
	 * @return void
	 * @throws E\UpdatingLockedSetException
	 */
	function checkLock()
	{
		if ($this->locked) {
			throw new E\UpdatingLockedSetException;
		}
	}



	/** @return void */
	function __clone()
	{
		$this->locked = FALSE;
	}



	// === \Iterator ====================================

	/** @return void */
	function rewind()
	{
		reset($this->items);
	}



	/** @return int */
	function key()
	{
		return key($this->items);
	}



	/** @return mixed */
	function current()
	{
		return current($this->items);
	}



	/** @return void */
	function next()
	{
		next($this->items);
	}



	/** @return bool */
	function valid()
	{
		return current($this->items) !== FALSE;
	}



	// === \Countable ====================================

	/** @return int */
	function count()
	{
		return count($this->items);
	}

}
