<?php
require_once __DIR__ . '/../Envtesting.php';

use \envtesting\Check;
use \envtesting\TestSuit;

/**
 * Example show how to autoload all test files and check them
 *
 * @author Roman Ozana <roman@wikidi.com>
 */

// ---------------------------------------------------------------------------------------------------------------------

class PhpFilterIterator extends RecursiveFilterIterator {
	/**
	 * @return bool
	 */
	public function accept() {
		return $this->current()->getExtension() === 'php';
	}
}

$iterator = new RecursiveIteratorIterator(
	new PhpFilterIterator(
		new RecursiveDirectoryIterator($dir = __DIR__ . '/../tests/library')
	), RecursiveIteratorIterator::SELF_FIRST
);

// ---------------------------------------------------------------------------------------------------------------------

$suit = new TestSuit('All librarys autoload');

foreach ($iterator as $filePath => $fileInfo/** @var SplFileInfo $fileInfo */) {
	// add tests to test suit
	$suit->addTest(
		$fileInfo->getBasename('.php'),
		Check::file($filePath, '')
	)->setType('library');
}

echo $suit->run();