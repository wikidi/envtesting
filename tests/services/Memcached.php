<?php
require_once __DIR__ . ' /../../Envtesting.php';

/**
 * Test memcached service
 *
 * @author Roman Ozana <roman@wikidi.com>
 */
class MemcachedTest {

	public static function connection($serverName, $dsn) {
		try {
			$memcache = new \Memcache();
			$memcache->addServer($server->host, $server->port, true, $server->weight);

			if ($memcache->getStats() == false) {

				throw new \envtesting\Error('Memcached connection faild: ' . $serverName, $dsn
				));
			}
			$mc->setCompressThreshold(2097152, 0.1);
		} catch (\Exception $e) {
			throw new \envtesting\Error('Memcached connection faild' . $e->getMessage());
		}
	}

}


MemcachedTest::connection('local', 'a');