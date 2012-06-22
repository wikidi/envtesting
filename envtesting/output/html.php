<?php
namespace envtesting\output;

use envtesting\Tests;
use envtesting\SuitGroup;

/**
 * Generate HTML output of envtesting test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Html {

	/** @var array */
	public $elements = array();

	/** @var string */
	public $title = 'Envtesting';

	/**
	 * @param string $title
	 */
	public function __construct($title = 'Envtesting') {
		$this->title = $title;
	}

	/**
	 * @param Tests|SuitGroup $element
	 * @return Html
	 */
	public function add($element) {
		$this->elements[] = $element;
		return $this;
	}

	/**
	 * Render HTML output
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function render() {
		extract((array)$this);
		require __DIR__ . '/layout.phtml';
	}

	/**
	 * @param string $title
	 * @return Html
	 */
	public static function instance($title = 'Envtesting') {
		return new self($title);
	}

}