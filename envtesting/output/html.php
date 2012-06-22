<?php
namespace envtesting\output;

use envtesting\TestSuit;
use envtesting\SuitGroup;

/**
 * Generate HTML output of envtesting test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Html {
	/** @var null|string */
	private $layout = null;
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
	 * @param TestSuit|SuitGroup $element
	 * @return \envtesting\outputing\Html
	 */
	public function add($element) {
		$this->elements[] = $element;
		return $this;
	}

	/**
	 * Render HTML output
	 *
	 * @throws \Exception
	 */
	public function render() {
		extract((array)$this);
		require __DIR__ . '/layout.phtml';
	}
}