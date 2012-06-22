<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require_once __DIR__ . '/ShrinkPHP.php';
$shrink = new ShrinkPHP();
$shrink->useNamespaces = true;
$shrink->addDir(__DIR__ . '/../envtesting/');

file_put_contents(__DIR__ . '/../Envtesting.php', $shrink->getOutput());