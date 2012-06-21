<?php
namespace envtesting\outputing;
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
	 * @return null|string
	 */
	public function getLayout() {
		if ($this->layout === null) $this->layout = __DIR__ . '/layout.phtml';
		return $this->layout;
	}

	/**
	 * @param string $layout
	 * @return \envtesting\outputing\Html
	 */
	public function setLayout($layout) {
		$this->layout = $layout;
		return $this;
	}

	/**
	 * Render HTML output
	 *
	 * @throws \Exception
	 */
	public function render() {
		if (file_exists($layout = $this->getLayout())) {
			extract((array)$this);
			require $layout;
		} else {
			throw new \Exception('Template "' . $layout . '" not exists');
		}

	}
}