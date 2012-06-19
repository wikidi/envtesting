<?php
namespace src\envtesting;

/**
 * Fatal error in test
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Error extends \Exception {
}

/**
 * Only warning (something went wrong, but still working)
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Warning extends \Exception {
}