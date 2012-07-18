<?php
/**
 * Example how to check permission for some dir or file
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = new \envtesting\Suite('memcached');

$suit->addTest(
	'memcache', function() {
		\tests\application\Permission::check(__DIR__, 777, __DIR__);
	}, 'service'
); // KISS
echo $suit->run();


