<?php
namespace src\envtesting;

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
			'status' => $this->getStatus(),
			'name' => $this->getName(),
			'type' => $this->getType(),
			'options' => empty($this->options) ? ' - ' : json_encode((object)$this->options),
			'message' => $this->getStatusMessage(),
		);

		return implode($response, ' | ');
	}
}