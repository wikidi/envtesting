<?php
namespace envtests\library;

/**
 * Check if MongoDB Native Driver is loaded
 *
 * @see http://php.net/manual/en/book.mongo.php
 * @author Roman Ozana <ozana@omdesign.cz>
 */

use envtesting\Assert;
use envtesting\Check;

Assert::true(Check::cls('MongoDB'), 'MongoDB class not found');

echo 'mongo found';