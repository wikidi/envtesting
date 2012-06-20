<?php
namespace envtesting\tests;
require_once __DIR__ . ' /../../Envtesting.php';

/**
 * Test memcached service
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class Memcached {

	public static function connection($host, $port) {
		try {
			$memcache = new \Memcache();
			$memcache->connect($host, $port, true);

			if ($memcache->getStats() == false) {
				throw new \envtesting\Error('Memcached connection faild: ' . $host . ':' . $port);
			}
		} catch (\Exception $e) {
			throw new \envtesting\Error('Memcached connection faild: ' . $host . ':' . $port . ' with ' . $e->getMessage());
		}
	}

	/**
	 * @param string $host
	 * @param string|integer $port
	 */
	public function __invoke($host, $port) {
		$this->connection($host, $port);
	}
}