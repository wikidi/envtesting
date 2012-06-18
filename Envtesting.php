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
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Assert {
	/**
	 * Compare $actual and $expected return true when it's equal
	 *
	 * @param mixed $actual
	 * @param mixed $expected
	 * @param null $message
	 * @throws Error
	 */
	public static function same($actual, $expected, $message = null) {
		if ($actual !== $expected) {
			throw new Error($message);
		}
	}

	/**
	 *
	 * @param string|null $message
	 * @throws Error
	 */
	public static function fail($message = null) {
		throw new Error($message);
	}

	/**
	 * Throw
	 *
	 * @param $value
	 * @param null|string $message
	 * @throws Error
	 */
	public static function true($value, $message = null) {
		if ($value !== true) {
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
	 * Use file for checking result
	 *
	 * @param $file
	 * @return closure
	 */
	public static function useFile($file, $dir = __DIR__) {
		return function () use ($file, $dir) {
			require_once $dir . DIRECTORY_SEPARATOR . $file;
		};
	}
}


/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Test {
	/** @var string */
	public $name = '';
	/** @var null */
	public $callback = null;
	/** @var null */
	public $type = null;
	/** @var array */
	public $options = array();
	/** @var null|\Exception */
	public $result = false;

	public function __construct($callback, $name = '', $type = 'app', array $options = array()) {
		$this->callback = $callback;
		$this->name = $name;
		$this->type = $type;
		$this->options = $options;
	}

	/**
	 * @return Test
	 */
	public function run() {
		call_user_func($this->callback);
		return $this;
	}

	/**
	 * @param mixed|string|\Exception $result
	 */
	public function setResult($result) {
		$this->result = $result;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		if (is_string($this->result)) return $this->result;
		if ($this->result instanceof \envtesting\Error) return 'ERROR';
		if ($this->result instanceof \envtesting\Warning) return 'WARNING';
		if ($this->result instanceof \Exception) return $this->result->getMessage();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getStatus() . ' | ' . $this->name . ' | ' . $this->type . ' | ' . implode(
			', ', $this->options
		);
	}
}


/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class TestSuit implements \ArrayAccess, \IteratorAggregate {

	/** @var array */
	private $tests = array();

	/** @var string */
	private $name = __CLASS__;

	/**
	 * @param string $name
	 */
	public function __construct($name = __CLASS__) {
		$this->name = $name;
	}

	/**
	 * @param $callback
	 * @param $name
	 * @param array $options
	 * @throws \Exception
	 */
	public function call($callback, $name, $type, array $options = array()) {
		if (is_callable($callback)) {
			$this->tests[] = new Test($callback, $name, $type, $options);
		} else {
			throw new \Exception('Given callback not callable');
		}
	}

	/**
	 * @return TestSuit
	 */
	public function shuffle() {
		shuffle($this->tests);
		return $this;
	}

	/**
	 * @return TestSuit
	 */
	public function run() {
		foreach ($this->tests as $key => $test) {
			/** @var \envtesting\Test $test */
			try {
				$test->run()->setResult('OK');
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


	public function offsetSet($offset, $value) {
		if (!$value instanceof Test) throw new \Exception('Usupported type');
		if ($offset === null) {
			$this->tests[] = $value;
		} else {
			$this->tests[$offset] = $value;
		}
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset) {
		if (isset($this->tests[$offset])) unset($this->tests[$offset]);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return str_repeat(':', 80) . PHP_EOL . str_pad($this->name, 80, ' ', STR_PAD_BOTH) . PHP_EOL . str_repeat(
			':', 80
		) . PHP_EOL . implode(
			PHP_EOL, $this->tests
		) . PHP_EOL;
	}

	public function getIterator() {
		return new \ArrayIterator($this->tests);
	}
}



/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Error extends \Exception {
}

/**
 * @author Roman Ozana <ozana@omdesign.cz>Î
 */
class Warning extends \Exception {
}