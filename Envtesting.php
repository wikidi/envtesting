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
 * Class autoloading and app tools
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Autoload {

	/** @var null|boolean */
	private static $update = true;
	/** @var bool */
	private static $init = true;
	/** @var array */
	private static $paths = array();

	/**
	 * Add path for autoloading
	 *
	 * @static
	 * @param string $path
	 */
	public static function addPath($path) {
		self::$paths[] = $path;
		self::$update = true;
	}

	/**
	 * @static
	 * @param string $className
	 * @return bool
	 */
	public static function cls($className) {
		if (self::$init) {
			self::$paths[] = get_include_path();
			self::$paths[] = dirname(__DIR__); // envtesting home dir
			self::$init = false; // init just once
		}

		// update path
		if (self::$update) {
			set_include_path(implode(PATH_SEPARATOR, self::$paths));
			self::$update = false;
		}

		$className = ltrim($className, '\\');
		$fileName = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR; //namespace replace
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php'; //pear replace on classname only
		return (bool)@include_once $fileName;
	}
}

spl_autoload_register(array('envtesting\Autoload', 'cls'));


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
	/** @var string */
	protected $notice = '';
	/** @var null|\Exception */
	protected $result = null;

	/**
	 * @param string $name
	 * @param mixed $callback
	 * @param null|string $type
	 */
	public function __construct($name, $callback, $type = null) {
		$this->name = $name;
		$this->callback = $callback;
		$this->type = $type;
	}

	/**
	 * Setup callback parametters
	 *
	 * @return Test
	 */
	public function withOptions() {
		$this->options = func_get_args();
		return $this;
	}

	/**
	 * Perform test
	 *
	 * @return Test
	 */
	public function run() {
		try {
			$this->result = 'OK';
			call_user_func_array($this->getCallback(), $this->getOptions());
		} catch (Error $error) {
			$this->setResult($error);
		} catch (Warning $warning) {
			$this->setResult($warning);
		} catch (\Exception $e) {
			$this->setResult($e);
		}
		return $this;
	}

	/**
	 * @return Test
	 */
	public function __invoke() {
		return $this->run();
	}

	/**
	 * Return test status string
	 *
	 * @throws \Exception
	 * @return string|null
	 */
	public function getStatus() {
		if (is_scalar($this->getResult())) return (string)$this->getResult();

		if ($this->isError()) return 'ERROR';
		if ($this->isWarning()) return 'WARNING';
		if ($this->isException()) return 'EXCEPTION';

		throw new \Exception('Invalid result type: ' . gettype($this->result)); // array, resource, unknown object
	}

	/**
	 * Return status message
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getStatusMessage() {
		return ($this->result instanceof \Exception) ? $this->result->getMessage() : '';
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Results
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Return true when test fails by warning
	 *
	 * @return bool
	 */
	public function isWarning() {
		return $this->getResult() instanceof Warning;
	}

	/**
	 * Return true when test fails by error
	 *
	 * @return bool
	 */
	public function isError() {
		return $this->getResult() instanceof Error;
	}

	/**
	 * Return true when test is OK
	 *
	 * @return bool
	 */
	public function isOk() {
		return !$this->isException();
	}

	/**
	 * Return true when test generate Exception
	 *
	 * @return bool
	 */
	public function isException() {
		return $this->getResult() instanceof \Exception;
	}

	/**
	 * @return bool|\Exception|null
	 */
	public function getResult() {
		if ($this->result === null) $this->run();
		return $this->result;
	}

	/**
	 * @param \Exception|string|null $result
	 * @return Test
	 */
	public function setResult($result) {
		$this->result = $result;
		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Response
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function __toString() {
		$response = array(
			'status' => str_pad($this->getStatus(), 10, ' '),
			'name' => str_pad($this->getName(), 20, ' '),
			'type' => str_pad($this->getType(), 10, ' '),
			'options' => json_encode((object)$this->getOptions()),
			'notice' => $this->getNotice(),
			'message' => $this->getStatusMessage(),
		);

		return implode($response, ' | ');
	}

	// -------------------------------------------------------------------------------------------------------------------
	// Setters and getters
	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * Return callback
	 *
	 * @throws \Exception
	 * @return \Reflector
	 */
	public function getCallback() {
		if (is_callable($this->callback)) return $this->callback;

		// static callback
		if (is_string($this->callback) && strpos($this->callback, '::')) {
			return $this->callback = explode('::', $this->callback);
		}

		throw new \Exception('Invalid callback');
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @param string $name
	 * @return Test
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return null
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string|null $type
	 * @return Test
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * @param array $options
	 * @return Test
	 */
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNotice() {
		return $this->notice;
	}

	/**
	 * @param string $notice
	 * @return Test
	 */
	public function setNotice($notice) {
		$this->notice = $notice;
		return $this;
	}

	// -------------------------------------------------------------------------------------------------------------------

	/**
	 * @static
	 * @param string $name
	 * @param mixed $callback
	 * @param string|null $type
	 * @return Test
	 */
	public static function instance($name, $callback, $type = null) {
		return new self($name, $callback, $type);
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
			$test->run();
		}
		return $this;
	}

	/**
	 * @return TestSuit
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
	 * @param mixed $callback
	 * @param null $type
	 * @return Test
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
	 * Return new instance of TestSuit
	 *
	 * @static
	 * @param string $name
	 * @return TestSuit
	 */
	public static function instance($name) {
		return new self($name);
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

/**
 * Envtesting application
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class App {
	/** @var string */
	public static $root = __DIR__;
}