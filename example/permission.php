<?php
/**
 * Example how to check permission for some dir or file
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suit = new \envtesting\Suite('permission');

$suit->addTest(
	'permission', function() {
		\tests\application\Permission::check(__DIR__, 777, __DIR__);
	}, 'app'
);

echo $suit->run();


