<?php
require_once __DIR__ . '/../Envtesting.php';

/**
 * Generat CSV output
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
\envtesting\Suite::instance('Simple test')->addFromDir(
	\envtesting\App::$root . '/tests/library', 'library'
)->run()->render('csv');