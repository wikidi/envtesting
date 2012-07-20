<?php
/**
 * Example how to check permission for some dir or file
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/../Envtesting.php';

$suite = new \envtesting\Suite('permission');

$suite->addTest(
	'permission', function() {
		\envtests\application\Permission::check(__DIR__, 777, __DIR__);
	}, 'app'
);


echo $suite->run();


