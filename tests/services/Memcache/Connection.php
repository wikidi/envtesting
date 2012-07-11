<?php
namespace tests\services\memcache;

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
		try {
			$memcache = new \Memcache();
			$memcache->connect($this->host, $this->port, true);

			if ($memcache->getStats() == false) {
				throw new \envtesting\Error('Memcached connection faild: ' . $this->host . ':' . $this->port);
			}

		} catch (\Exception $e) {
			throw new \envtesting\Error(
				'Memcached connection faild: ' . $this->host . ':' . $this->port . ' with ' . $e->getMessage()
			);
		}
	}

	public function __toString() {
		return $this->host . ':' . $this->port;
	}
}