<?php
namespace tests\application;

/**
 * Check file permission
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
final class Permission {

	/**
	 * @param string $path
	 * @param int $permission
	 * @throws \envtesting\Warning
	 * @throws \envtesting\Error
	 */
	public static function check($path, $permission = 777, $msgPath = null) {
		if (!is_dir($path) && !is_file($path)) {
			throw new \envtesting\Warning('File or directory "' . $msgPath . '"not found.');
		}

		$current = substr(decoct(fileperms($path)), $permission > 1000 ? -4 : -3);
		if ($current < $permission) {
			throw new \envtesting\Error('Invalid permission permission ' . $current . ' for "' . $msgPath . '"');
		}
	}
}