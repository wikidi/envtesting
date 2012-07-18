<?php
namespace envtests\application;

/**
 * Validate minicrawler version
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */

class Minicrawler {

	/** @var string */
	public $version = null;

	public function __construct($version = null) {
		$this->version = $version;
	}

	public function __invoke() {
		$handle = popen('minicrawler', 'r');
		$read = trim(fgets($handle) . fgets($handle));
		pclose($handle);

		if (!$read) throw new \envtesting\Error('Minicrawler not found');

		if ($this->version && stristr($read, 'minicrawler, version ' . $this->version) == false) {
			throw new \envtesting\Error('Wrong minicrawler version' . $read);
		}

		return $read;
	}
}