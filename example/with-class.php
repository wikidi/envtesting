<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';
require_once __DIR__ . '/../tests/services/Memcached.php';

use \envtesting\Check;
use \envtesting\SuitGroup;
use \envtesting\TestSuit;


$suit = new \envtesting\TestSuit('memcached');
$suit->addTest('memcached', array('envtesting\tests\Memcached', 'connection'), 'service')->withOptions(
	'127.0.0.1', 11211
)->setType('service')->setNotice('notics');
$suit->addTest('memcached', 'envtesting\tests\Memcached::connection', 'service')->withOptions('127.0.0.1', 11211);
$suit->addTest('memcached', new Memcached(), 'service')->withOptions('127.0.0.1', 11211);

echo $suit->run();