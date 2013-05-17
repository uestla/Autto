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



	/** @param  string $type */
	function __construct($type)
	{
		$this->type = $type;
	}



	/**
	 * @param  mixed $item
	 * @return Set
	 * @throws E\InvalidItemTypeException
	 */
	function add($item)
	{
		$this->checkLock();

		if (!is_object($item) || !$item instanceof $this->type) {
			throw new E\InvalidItemTypeException;
		}

		$this->beforeAdd($item);
		$this->items[] = $item;

		return $this;
	}



	/**
	 * @param  mixed $item
	 * @return void
	 * @throws E\DuplicateItemException
	 */
	function beforeAdd($item)
	{
		$hash = spl_object_hash($item);
		if (isset($this->hashes[$hash])) {
			throw new E\DuplicateItemException;
		}

		$this->hashes[$hash] = TRUE;
	}



	/**
	 * @param  mixed $item
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
		foreach ($this as $item) {
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



	// === \IteratorAggregate ====================================

	/** @return \ArrayIterator */
	/* function getIterator()
	{
		return new \ArrayIterator($this->items);
	} */



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
