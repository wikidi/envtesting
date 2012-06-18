<?php
namespace src\envtesting;

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
