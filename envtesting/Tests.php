<?php
namespace envtesting;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 * @todo ignore tests group
 */
class Tests implements \ArrayAccess, \IteratorAggregate {

	/** @var array */
	protected $tests = array();

	/** @var string */
	protected $name = __CLASS__;

	/** @var null|string */
	protected $group = null;

	/**
	 * @param string $name
	 */
	public function __construct($name = __CLASS__) {
		$this->name = $name;
	}

	/**
	 * Randomize tests order in suit
	 *
	 * - when there is only one group of test shuffle inner test
	 * - on multiple groups shuffle only groups not test inside
	 *
	 * @return Tests
	 */
	public function shuffle($deep = false) {
		if (count($this->tests) === 1 || $deep) {
			//$this->tests =
			array_filter($this->tests, 'shuffle');

			//shuffle($this->tests);
		} else {
			shuffle($this->tests); // shuffle group
		}

		return $this;
	}

	/**
	 * Run all tests in test suit
	 *
	 * @return Tests
	 */
	public function run() {
		foreach ($this->tests as $tests) {
			foreach ($tests as $test/** @var Test $test */) {
				$test->run(); // TODO break group cycle after firs error / warning / exception
			}
		}
		return $this;
	}

	/**
	 * @return Tests
	 */
	public function __invoke() {
		return $this->run();
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
	 * @param mixed|filepath $callback
	 * @param null $type
	 * @return \envtesting\Test
	 */
	public function addTest($name, $callback, $type = null) {
		if (is_string($callback) && (is_file(__DIR__ . $callback) || is_file($callback))) {
			$callback = Check::file(basename($callback), dirname($callback));
		}
		return $this->tests[$this->getGroup()][] = Test::instance($name, $callback, $type);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$results = \envtesting\App::header($this->name);
		foreach ($this->tests as $group => $tests) {
			$results .= implode(PHP_EOL, $tests) . PHP_EOL;
		}
		return $results . PHP_EOL;
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Autoloading
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Autoload all PHP tests from directory
	 *
	 * @param string $dir
	 * @param string $type
	 * @return Tests
	 */
	public function addFromDir($dir, $type = '') {
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
		return array_key_exists($offset, $this->tests[$this->getGroup()]);
	}

	/**
	 * @param mixed $offset
	 * @return Test
	 */
	public function offsetGet($offset) {
		return $this->tests[$this->getGroup()][$offset];
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
			$this->tests[$this->getGroup()][] = $value;
		} else {
			$this->tests[$this->getGroup()][$offset] = $value;
		}
	}

	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset) {
		if (isset($this->tests[$this->getGroup()][$offset])) {
			unset($this->tests[$this->getGroup()][$offset]);
		}
	}

	/**
	 * @return \ArrayIterator|\Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator($this->tests);
	}


	/**
	 * @param string $name
	 * @return Tests
	 */
	public function __get($name) {
		return $this->to($name);

	}

	/**
	 * @todo return all tests without group
	 */
	public function getTests() {

	}

	/**
	 * Begin new tests group
	 *
	 * @param string $name
	 * @param string $title
	 * @return Tests
	 */
	public function to($name) {
		$this->group = $name;
		return $this;
	}

	/**
	 * @param $name
	 * @return null|string
	 * @todo get group by name
	 */
	public function getGroup($name = null) {
		return $this->group ? $this->group : 'main';
	}

	/**
	 * Return groups names
	 *
	 * @param string $name
	 * @return array
	 */
	public function getGroups() {
		return array_keys($this->tests);
	}

	/**
	 * Has tests groups
	 *
	 * @return bool
	 */
	public function hasGroups() {
		return count($this->tests) !== 1;
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Return new instance of Tests
	 *
	 * @param string $name
	 * @return Tests
	 */
	public static function instance($name) {
		return new self($name);
	}
}
