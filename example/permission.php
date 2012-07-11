<?php
/**
 * Example how to check permission for some dir or file
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = new \envtesting\Suit('memcached');
$suit->addTest(
	'memcache', function() {
		$tmp = substr(decoct(fileperms('tmp')), -3); // or -4
		if ($tmp < 777) throw new \envtesting\Error('Invalid folder permission');
	}, 'service'
); // KISS
echo $suit->run();


