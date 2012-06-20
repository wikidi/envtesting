<?php
namespace src\envtesting;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class TestSuit implements \ArrayAccess, \IteratorAggregate {

	/** @var array */
	protected $tests = array();

	/** @var string */
	protected $name = __CLASS__;

	/**
	 * @param string $name
	 */
	public function __construct($name = __CLASS__) {
		$this->name = $name;
	}

	/**
	 * Randomize tests order in suit
	 * does not matter on test groups
	 *
	 * @return TestSuit
	 */
	public function shuffle() {
		shuffle($this->tests);
		return $this;
	}

	/**
	 * Run all tests in test suit
	 *
	 * @return TestSuit
	 */
	public function run() {
		foreach ($this->tests as $key => $test/** @var \envtesting\Test $test */) {
			$test->run();
		}
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
	 * Add new callback test to suit
	 *
	 * @param string $name
	 * @param mixed $callback
	 * @param null $type
	 * @return \src\envtesting\Test
	 */
	public function addTest($name, $callback, $type = null) {
		return $this->tests[] = Test::instance($name, $callback, $type);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return str_repeat(':', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . str_repeat(':', 80) .
			PHP_EOL . implode(PHP_EOL, $this->tests) . PHP_EOL;
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Autoloading
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Autoload all PHP tests from directory
	 *
	 * @param string $dir
	 * @param string $type
	 * @return TestSuit
	 */
	public function fromDir($dir, $type = '') {
		$iterator = new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)), '/\.php$/i');

		foreach ($iterator as $filePath => $fileInfo/** @var SplFileInfo $fileInfo */) {
			// add tests to test suit
			$this->addTest(
				$fileInfo->getBasename('.php'),
				Check::file($filePath, '')
			)->setType($type);
		}

		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Interfaces implementation
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->tests);
	}

	/**
	 * @param mixed $offset
	 * @return Test
	 */
	public function offsetGet($offset) {
		return $this->tests[$offset];
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
			$this->tests[] = $value;
		} else {
			$this->tests[$offset] = $value;
		}
	}

	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset) {
		if (isset($this->tests[$offset])) {
			unset($this->tests[$offset]);
		}
	}

	/**
	 * @return \ArrayIterator|\Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator($this->tests);
	}


	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @static
	 * @param string $name
	 * @param mixed $callback
	 * @param string|null $type
	 * @return TestSuit
	 */
	public static function instance($name) {
		return new self($name);
	}
}
