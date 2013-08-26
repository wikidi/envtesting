<?php
namespace envtesting;
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Generat CSV output
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
$suite = Suite::instance('CSV sample');

$suite->addTest('csv', function(){ return 'response'; }, 'lib');
$suite->addTest('csv', function(){ return new Error('test' . PHP_EOL . 'now line' . PHP_EOL);}, 'lib');
$suite->run()->render('csv');