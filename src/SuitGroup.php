<?php
namespace src\envtesting;

/**
 * Group of test suits
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class SuitGroup implements \IteratorAggregate {

	/** @var array */
	protected $suits = array();

	/**
	 * Randomize order in test suits
	 *
	 * @return SuitGroup
	 */
	public function shuffle() {
		shuffle($this->suits);
		return $this;
	}


	/**
	 * Add testSuit to group
	 *
	 * @param string $name
	 * @return TestSuit
	 * @throws \Exception
	 */
	public function addSuit($name) {
		if (array_key_exists($name, $this->suits)) {
			throw new \Exception('TestSuit "' . $name . '" already exists');
		}
		return $this->suits[$name] = new TestSuit($name);
	}

	/**
	 * @return \ArrayIterator|\Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator($this->suits);
	}

	/**
	 * Execute all suits in SuitGroup
	 *
	 * @return SuitGroup
	 */
	public function run() {
		foreach ($this->suits as $suit/** @var $suit TestSuit */) {
			$suit->run();
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return implode($this->suits, PHP_EOL) . PHP_EOL;
	}

}