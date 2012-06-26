<?php
namespace envtesting;

/**
 * <code>
 * $suit = new Suit();
 * $tests->addTest('something', function () { throw new Error('wrong');}, 'lib');
 * $tests->addTest('something2', function () { throw new Error('wrong');}, 'lib');
 * $tests->group->addTest('something2', function () { throw new Warning('wrong');}, 'lib');
 * </code>
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Suit implements \ArrayAccess, \IteratorAggregate {

	/** @var array */
	protected $groups = array();

	/** @var string */
	protected $name = __CLASS__;

	/** @var null|string */
	protected $currentGroup = null;

	/** @var bool */
	protected $failGroupOnFirstError = false;

	/**
	 * @param string $name
	 */
	public function __construct($name = __CLASS__) {
		$this->name = $name;
	}

	/**
	 * Run all tests in test suit
	 *
	 * @return Suit
	 */
	public function run() {
		foreach ($this->groups as $tests) {
			$isError = false;
			foreach ($tests as $test/** @var Test $test */) {
				$test->run();
				$isError = $test->isError() && $this->failGroupOnFirstError;
			}
		}
		return $this;
	}


	/**
	 * Randomize tests order in suit
	 *
	 * - when there is only one group of test shuffle inner test
	 * - on multiple groups shuffle only groups not test inside
	 *
	 * @param bool $deep
	 * @return \envtesting\Suit
	 */
	public function shuffle($deep = false) {
		if ($this->hasGroups() === false || $deep) {
			array_filter($this->groups, 'shuffle');
		} else {
			shuffle($this->groups); // shuffle only groups
		}

		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Add test or tests
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Add new callback test to suit
	 *
	 * @param string $name
	 * @param mixed|callable|filepath $callback
	 * @param null $type
	 * @return Test
	 */
	public function addTest($name, $callback, $type = null) {
		if (is_string($callback) && (is_file(__DIR__ . $callback) || is_file($callback))) {
			$callback = Check::file(basename($callback), dirname($callback));
		}
		return $this->groups[$this->getCurrentGroupName()][] = Test::instance($name, $callback, $type);
	}

	/**
	 * Autoload all PHP files from directory
	 *
	 * @param string $dir
	 * @param string $type
	 * @return Suit
	 */
	public function addFromDir($dir, $type = '') {
		$iterator = new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)), '/\.php$/i');

		foreach ($iterator as $filePath => $fileInfo/** @var SplFileInfo $fileInfo */) {
			$this->addTest($fileInfo->getBasename('.php'), $filePath)->setType($type); // add tests to test suit
		}

		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @param string $name
	 * @return Suit
	 */
	public function __get($name) {
		return $this->to($name);
	}

	/**
	 * Begin new Suit group
	 *
	 * @param string $name
	 * @return Suit
	 */
	public function to($name) {
		$this->currentGroup = $name;
		return $this;
	}

	/**
	 * Return testSuit name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Suit
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Return current group name
	 * - default group name is "main"
	 *
	 * @return null|string
	 */
	public function getCurrentGroupName() {
		return $this->currentGroup ? $this->currentGroup : 'main';
	}

	/**
	 * Return groups names
	 *
	 * @return array
	 */
	public function getGroupsNames() {
		return array_keys($this->groups);
	}

	/**
	 * Is there more then one group?
	 *
	 * @return bool
	 */
	public function hasGroups() {
		return count($this->groups) !== 1;
	}

	/**
	 * @param bool $group
	 */
	public function failGroupOnFirstError($fail = true) {
		$this->failGroupOnFirstError = $fail;
		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Return new instance of Suit
	 *
	 * @param string $name
	 * @return Suit
	 */
	public static function instance($name) {
		return new self($name);
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Interfaces implementation
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->groups[$this->getCurrentGroupName()]);
	}

	/**
	 * @param mixed $offset
	 * @return Test
	 */
	public function offsetGet($offset) {
		return $this->groups[$this->getCurrentGroupName()][$offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @throws \Exception
	 */
	public function offsetSet($offset, $value) {
		if (!$value instanceof Test) {
			throw new \Exception('Usupported test type');
		}

		if ($offset === null) {
			$this->groups[$this->getCurrentGroupName()][] = $value;
		} else {
			$this->groups[$this->getCurrentGroupName()][$offset] = $value;
		}
	}

	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset) {
		if (isset($this->groups[$this->getCurrentGroupName()][$offset])) {
			unset($this->groups[$this->getCurrentGroupName()][$offset]);
		}
	}

	/**
	 * @return \ArrayIterator|\Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator($this->groups);
	}

	// -------------------------------------------------------------------------------------------------------------------
	// outputing
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function __toString() {
		$results = \envtesting\App::header($this->name);
		foreach ($this->groups as $group => $tests) {
			$results .= implode(PHP_EOL, $tests) . PHP_EOL;
		}
		return $results . PHP_EOL;
	}

}

