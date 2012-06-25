<?php
namespace envtesting;

/**
 * Test is object cover for callback that perform test
 *
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
			'options' => $this->hasOptions() ? json_encode((object)$this->getOptions()) : '',
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
	 * @return bool
	 */
	public function hasOptions() {
		return !empty($this->options);
	}

	/**
	 * @param array $options
	 * @return Test
	 */
	public function setOptions(array $options) {
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
	 * Return instance of test
	 *
	 * <code>
	 * Test::instance('something', function(){ return true; }, 'dummy');
	 * </code>
	 *
	 * @param string $name
	 * @param mixed $callback
	 * @param string|null $type
	 * @return Test
	 */
	public static function instance($name, $callback, $type = null) {
		return new self($name, $callback, $type);
	}
}