<?php
namespace src\envtesting;

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