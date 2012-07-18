<?php
namespace envtests\application;
use envtesting\Error;

/**
 * Try store session to session storage and get response back
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Session {

	/** @var string */
	public $savePath = null;

	/**
	 * @param string $savePath
	 */
	public function __construct($savePath) {
		$this->savePath = $savePath;
	}

	public function __invoke() {
		$this->tryConnectSession();
		$this->tryReadAndWrite();
	}

	public function tryConnectSession() {
		try {
			session_save_path($this->savePath);
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