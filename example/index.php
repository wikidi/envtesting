<?php
namespace envtesting;
/**
 * Copy & Paste example
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

Throws::allErrors(); // throws everything (warnings, notices, PHP fatals) as Exception

Suite::instance('Envtesting examples'); // change Suite name
//Suite::instance()->failGroupOnFirstError(); // fail whole group on first error

// Create new group
$group = Suite::instance()->group; /** @var Suite $group */

// ERROR
$group->addTest('return', function(){ return new Error();}, 'return');
$group->addTest('throw', function(){ throw new Error();}, 'throw');
$group->addTest('return', function(){ return new Error('error with message');}, 'return');

// warning
$group->addTest('return', function(){ return new Warning();}, 'return');
$group->addTest('return', function(){ throw new Warning();}, 'return');
$group->addTest('return', function(){ return new Warning('warning with message');}, 'return');


// check some tests
$libs = Suite::instance()->libs;
$libs->addTest('APC', dirname(__DIR__) . '/envtests/library/Apc.php', 'library');
$libs->addTest('PDO', dirname(__DIR__) . '/envtests/library/Pdo.php', 'library');

// Returns
$returns = Suite::instance()->callback;
$returns->addTest('JSON', function(){ return array('array'=> 'ok');}, 'return');
$returns->addTest('JSON', function(){ return (object)array('stdClass'=> 'ok');}, 'return');
$returns->addTest('BOOL', function(){ return false;}, 'return');
$returns->addTest('BOOL', function(){ return true;}, 'return');
$returns->addTest('empty', function(){ /* empty */ }, 'return');
$returns->addTest('NULL', function(){ return null; }, 'return');

// callbacks
$callback = Suite::instance()->callback;
$callback->addTest('MISSING', 'missing file', 'callback');
$callback->addTest('PHP BUG', function(){ return $response; }, 'callback');

$multiline = Suite::instance()->multiline;
$multiline->addTest('PHP BUG', function(){ throw new Warning('add' . PHP_EOL . 'multiline' . PHP_EOL . 'text'); }, 'callback');
$multiline->addTest('PHP BUG', function(){ return 'ok' . PHP_EOL . 'multiline' . PHP_EOL . 'text'; }, 'callback');

// render HTML
Suite::instance()->run()->render();

Throws::nothing();