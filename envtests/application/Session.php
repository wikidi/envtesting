<?php
namespace envtests\application;
use envtesting\Error;

/**
 * Try store session to session storage and get response back
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachař <jan.prachar@gmail.com>
 */
class Session {

	/** @var Object */
	public $config;

	/**
	 * @param string|Object $config
	 */
	public function __construct($config) {
		if (is_string($config)) {
			$this->config = new \stdClass;
			$this->config->save_path = $config;
		} else {
			$this->config = $config;
		}
	}

	public function __invoke() {
		$this->tryConnectSession();
		$this->tryReadAndWrite();
	}

	public function tryConnectSession() {
		try {
			if (isset($this->config->save_handler)) {
				if (ini_set('session.save_handler', $this->config->save_handler) === false) throw new Error('Session failed: cannot set save_handler');
			}
			if (isset($this->config->save_path)) {
				if (ini_set('session.save_path', $this->config->save_path) === false) throw new Error('Session failed: cannot set save_path');
			}
			if (!session_start()) throw new Error('Session failed: session was not created');
		} catch (\Exception $e) {
			throw new Error('Session failed: ' . $e->getMessage());
		}
	}

	private function tryReadAndWrite() {
		$_SESSION['test'] = 'value';
		if ($_SESSION['test'] != 'value') {
			throw new Error('Session failed: session write failed');
		}
		session_write_close();
		session_start();
		if ($_SESSION['test'] != 'value') {
			throw new Error('Session failed: reopened session read failed');
		}
	}

}
