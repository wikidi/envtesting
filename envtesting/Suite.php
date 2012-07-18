<?php
namespace envtesting;

/**
 * Suite is envelope for tests and test groups
 *
 * <code>
 * $suite = new Suite();
 * $tests->addTest('something', function () { throw new Error('wrong');}, 'lib');
 * $tests->addTest('something2', function () { throw new Error('wrong');}, 'lib');
 * $tests->group->addTest('something2', function () { throw new Warning('wrong');}, 'lib');
 * </code>
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Suite implements \ArrayAccess, \IteratorAggregate {

	/** @var array */
	protected $groups = array();

	/** @var string */
	protected $name = null;

	/** @var null|string */
	protected $currentGroup = null;

	/** @var bool */
	protected $failGroupOnFirstError = false;

	/** @var Filter */
	protected $filter = null;

	/**
	 * @param string $name
	 * @param Filter|null $filter
	 */
	public function __construct($name = __CLASS__, Filter $filter = null) {
		$this->name = $name;
		$this->filter = ($filter) ? $filter : new Filter();
	}

	/**
	 * Run all tests in test suit
	 *
	 * @return Suite
	 */
	public function run() {
		foreach ($this->groups as $tests) {
			$failed = false;
			foreach ($tests as $test/** @var Test $test */) {
				if ($failed) {
					$test->setResult($failed->getResult());
					$test->setNotice($failed->getNotice());
					$test->setOptions($failed->getOptions());
				} else {
					$test->run(); // execute test
					$failed = ($test->isFail() && $this->failGroupOnFirstError) ? $test : false; // on first error halt
				}
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
	 * @return \envtesting\Suite
	 */
	public function shuffle($deep = false) {
		if ($deep || $this->hasGroups() === false) array_filter($this->groups, 'shuffle');

		if ($this->hasGroups()) {
			// @see http://php.net/manual/en/function.shuffle.php
			$keys = array_keys($this->groups);
			shuffle($keys);
			$this->groups = array_merge(array_flip($keys), $this->groups);
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

		// create new Test instance
		$test = Test::instance($name, $callback, $type);
		$test->enable($this->filter->isValid($test, $this));

		return $this->groups[$this->getCurrentGroupName()][] = $test;
	}

	/**
	 * Autoload all PHP files from directory
	 *
	 * @param string $dir
	 * @param string $type
	 * @return Suite
	 */
	public function addFromDir($dir, $type = '') {
		$iterator = new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)), '/\.php$/i');

		foreach ($iterator as $filePath => $fileInfo/** @var SplFileInfo $fileInfo */) {
			$this->addTest($fileInfo->getBasename('.php'), $filePath, $type); // add tests to test suit
		}

		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @param string $name
	 * @return Suite
	 */
	public function __get($name) {
		return $this->to($name);
	}

	/**
	 * Begin new Suite group
	 *
	 * @param string $name
	 * @return Suite
	 */
	public function to($name) {
		$this->currentGroup = $name;
		if (!array_key_exists($name, $this->groups)) $this->groups[$name] = array();
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
	 * @return Suite
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
		return count($this->groups) > 1;
	}

	/**
	 * @param Filter $filter
	 * @return Suite
	 */
	public function setFilter(Filter $filter) {
		$this->filter = $filter;
		return $this;
	}

	/**
	 * @return Filter|null
	 */
	public function getFilter() {
		return $this->filter;
	}

	/**
	 * Failed whole group when something went wrong in first test
	 *
	 * @param bool $fail
	 * @return \envtesting\Suite
	 */
	public function failGroupOnFirstError($fail = true) {
		$this->failGroupOnFirstError = $fail;
		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Return new instance of Suite
	 *
	 * @param string $name
	 * @param Filter|null $filter
	 * @return Suite
	 */
	public static function instance($name, Filter $filter = null) {
		return new self($name, $filter);
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
	 * @return Test|mixed
	 */
	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->groups[$this->getCurrentGroupName()][$offset] : null;
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


	/**
	 * @param null|string $to
	 * @return mixed
	 */
	public function render($to = null) {
		if ($to === null && isset($_GET['output'])) {
			$to = $_GET['output'] === 'csv' ? 'csv' : 'html';
		} elseif ($to === null && PHP_SAPI === 'cli') {
			$to = 'cli';
		}

		switch ($to) {
			case 'cli': // PHP client
				echo $this;
				break;
			case 'csv': // CSV output
				echo \envtesting\output\Csv::render($this);
				break;
			case 'html': // HTML output
			default:
				echo \envtesting\output\Html::render($this);
				break;
		}
	}

}

