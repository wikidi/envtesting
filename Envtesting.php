<?php
namespace envtesting;

/**
* Envtesting concated version
*
* @see https://github.com/wikidi/envtesting
*
* !!! Don't edit this file it's generated !!!
*/



/**
 * Group of basic asserts
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Assert {

	/**
	 * Compare $actual and $expected return true when it's equal
	 *
	 * @param mixed $actual
	 * @param mixed $expected
	 * @param string|null $message
	 * @throws Error
	 * @return void
	 */
	public static function same($actual, $expected, $message = null) {
		if ($actual !== $expected) {
			throw new Error($message);
		}
	}

	/**
	 * Make instant fail
	 *
	 * @param string|null $message
	 * @throws Error
	 * @return void
	 */
	public static function fail($message = null) {
		throw new Error($message);
	}

	/**
	 * Is value === true
	 *
	 * @param mixed $value
	 * @param null|string $message
	 * @throws Error
	 * @return void
	 */
	public static function true($value, $message = null) {
		if ($value !== true) {
			throw new Error($message);
		}
	}

	/**
	 * Is value === false
	 *
	 * @param mixed $value
	 * @param null|string $message
	 * @throws Error
	 * @return void
	 */
	public static function false($value, $message = null) {
		if ($value !== false) {
			throw new Error($message);
		}
	}

}


/**
 * Basic envtesting check
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class Check {

	/**
	 * Check if library and function exists
	 *
	 * @param string $extensionName
	 * @param string $infoFunction
	 * @return bool|string
	 */
	public static function lib($extensionName, $infoFunction = '') {
		return extension_loaded($extensionName) && ($infoFunction === '' || function_exists($infoFunction));
	}

	/**
	 * Check if class exists
	 *
	 * @param string $className
	 * @return bool
	 */
	public static function cls($className) {
		return class_exists($className);
	}

	/**
	 * Check php.ini
	 *
	 * - check value and return boolean response if same or
	 * - return value of variable
	 *
	 * @param mixed $variable
	 * @param null|mixed $value
	 * @return bool
	 */
	public static function ini($variable, $value = null) {
		return ($value === null) ? ini_get($variable) : $value === ini_get($variable);
	}

	/**
	 * Use PHP file for checking result
	 *
	 * @param string $file
	 * @param string $dir
	 * @return mixed callback
	 */
	public static function file($file, $dir = __DIR__) {
		return function () use ($file, $dir) {
			include $dir . DIRECTORY_SEPARATOR . $file;
		};
	}
}


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


/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Test {
	/** @var string */
	protected $name = '';
	/** @var callable|null */
	protected $callback = null;
	/** @var null */
	protected $type = null;
	/** @var array */
	protected $options = array();
	/** @var null|\Exception */
	protected $result = false;

	/**
	 * @param string $name
	 * @param mixed $callback
	 */
	public function __construct($name, $callback) {
		$this->name = $name;
		$this->callback = $callback;
	}

	/**
	 * Set test options given in callback call
	 *
	 * @param array $array
	 * @return Test
	 */
	public function setOptions(array $array) {
		$this->options = $array;
		return $this;
	}

	/**
	 * Set test type (
	 *
	 * @param string $type
	 * @return Test
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * Set test result
	 *
	 * @param mixed|string|\Exception $result
	 * @return void
	 */
	public function setResult($result) {
		$this->result = $result;
	}

	/**
	 * Execute test
	 *
	 * @throws \Exception
	 * @return Test
	 */
	public function run() {
		if (is_callable($this->callback)) {
			call_user_func($this->callback, $this->options);
		} else {
			throw new \Exception('Given callback is not callable');
		}
		return $this;
	}

	/**
	 * Return test status string
	 *
	 * @throws \Exception
	 * @return string|null
	 */
	public function getStatus() {
		if (is_scalar($this->result)) return (string)$this->result;
		if ($this->result instanceof Error) return 'ERROR';
		if ($this->result instanceof Warning) return 'WARNING';
		if ($this->result instanceof \Exception) return 'EXCEPTION';
		throw new \Exception('Invalid result type: ' . gettype($this->result)); // array, resource, unknown object
	}

	/**
	 * Return status message
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getStatusMessage() {
		if ($this->result instanceof \Exception) return $this->result->getMessage();
		return '';
	}


	/**
	 * Return test name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Return test type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Return callback
	 *
	 * @return mixed|null|callable
	 */
	public function getCallback() {
		return $this->callback;
	}

	/**
	 * Return Test result
	 *
	 * @return bool|\Exception|null
	 */
	public function getReult() {
		return $this->result;
	}


	/**
	 * Convert test to string
	 *
	 * @return string
	 */
	public function __toString() {
		$response = array(
			'status' => str_pad($this->getStatus(), 10, ' '),
			'name' => str_pad($this->getName(), 20, ' '),
			'type' => str_pad($this->getType(), 10, ' '),
			'options' => empty($this->options) ? ' - ' : json_encode((object)$this->options),
			'message' => $this->getStatusMessage(),
		);

		return implode($response, ' | ');
	}
}


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
			try {
				$test->run()->setResult('OK'); // result is OK
			} catch (Error $error) {
				$test->setResult($error);
			} catch (Warning $warning) {
				$test->setResult($warning);
			} catch (\Exception $e) {
				$test->setResult($e);
			}
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
	 * @param string $group
	 * @param mixed $callback
	 * @return Test
	 */
	public function addTest($name, $callback) {
		return $this->tests[] = new Test($name, $callback);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return str_repeat(':', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . str_repeat(':', 80) .
			PHP_EOL . implode(PHP_EOL, $this->tests) . PHP_EOL;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Interfaces implementation
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

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
}



/**
 * Fatal error in test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Error extends \Exception {
}

/**
 * Only warning (something went wrong, but still working)
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Warning extends \Exception {
}