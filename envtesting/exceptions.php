<?php
namespace envtesting;

/**
 * Cruel fatal error inside test
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
