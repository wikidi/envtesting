<?php
namespace tests\services;

/**
 * Test memcached service connection
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class MemcacheConnection {

	/** @var string */
	private $host = '127.0.0.1';
	/** @var int */
	private $port = 11211;

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
}