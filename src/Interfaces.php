<?php
namespace src\envtesting;

/**
 * @todo concept of post metadata interface can share data from TestClass to Test and TestSuit
 * @author Roman Ozana <ozana@omdesign.cz>
 */
interface TestMeta {
	/**
	 * Return test options
	 *
	 * @abstract
	 * @return array
	 */
	public function getOptions();
}