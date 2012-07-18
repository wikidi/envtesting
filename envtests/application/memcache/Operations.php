<?php
namespace envtests\application\memcache;
use envtests\services\memcache\Connection;
use envtesting\Error;

/**
 * Try read/write capabilities to memcache storage
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Operations extends Connection {

	/** @var string */
	public $key = '_envtesting_';

	/**
	 * @throws \envtesting\Error
	 * @return void
	 */
	public function __invoke() {

		// SET
		if (!$this->getMemcache()->set($this->key, uniqid())) {
			throw new Error('Set operation failed.' . PHP_EOL . $this);
		}
		// GET
		if ($this->getMemcache()->get($this->key) === false) {
			throw new Error('Get operation failed (no data found).' . PHP_EOL . $this);
		}
		// DELETE
		if (!$this->getMemcache()->delete($this->key)) {
			throw new Error('Delete operation failed.' . PHP_EOL . $this);
		}
	}
}