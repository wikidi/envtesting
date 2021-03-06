<?php
namespace envtests\services\memcache;
/**
 * Test memcached service connection
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class Connection {

	/** @var string */
	public $host = '127.0.0.1';
	/** @var int */
	public $port = 11211;
	/** @var null|Memcache */
	public $memcache = null;

	/**
	 * @param string $host
	 * @param string $port
	 */
	public function __construct($host, $port) {
		$this->host = $host;
		$this->port = $port;
	}

	/**
	 * @throws Error
	 * @return void
	 */
	public function __invoke() {
		$this->connect();
	}

	/**
	 * @throws \envtesting\Error
	 */
	public function connect() {
		if (!extension_loaded('memcache')) throw new \envtesting\Error('PHP extension \'memcache\' is not loaded');
		if (!class_exists('Memcache')) throw new \envtesting\Error('Memcache classs is missing.');

		try {
			$this->memcache = new \Memcache();
			$this->memcache->connect($this->host, $this->port, true);
			if ($this->memcache->getStats() == false) throw new \Exception('Memcache status return false.');
		} catch (\Exception $e) {
			throw new \envtesting\Error(
				'Memcached connection faild: ' . $this->host . ':' . $this->port . ' : ' . $e->getMessage()
			);
		}
	}

	/**
	 * @return Memcache|null
	 */
	public function getMemcache() {
		if ($this->memcache == null) $this->connect();
		return $this->memcache;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return 'memcache:://' . $this->host . ':' . $this->port;
	}
}