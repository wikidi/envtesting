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
	/** @var string */
	protected $name = __CLASS__;

	/**
	 * @param string $name
	 */
	public function __construct($name = __CLASS__) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

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
	 * @param TestSuit|null $suit
	 * @throws \Exception
	 * @return \src\envtesting\TestSuit
	 */
	public function addSuit($name, $suit = null) {
		if (array_key_exists($name, $this->suits)) {
			throw new \Exception('TestSuit "' . $name . '" already exists');
		}
		return $this->suits[$name] = $suit ? $suit : TestSuit::instance($name);
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
		return str_repeat('#', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL .
			implode($this->suits, PHP_EOL) . PHP_EOL . str_repeat('#', 80) . PHP_EOL;
	}

}