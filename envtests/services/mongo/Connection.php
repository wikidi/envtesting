<?php
namespace envtests\services\mongo;
use envtesting\Error;

/**
 * Check mongo connection
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Connection {

	/** @var string  */
	public $dsn;
	/** @var string */
	public $user;
	/** @var string */
	public $port;
	/** @var string */
	public $scheme;
	/** @var string */
	public $host;
	/** @var string */
	public $pass;
	/** @var string */
	public $dbname;
	/** @var array */
	public $options = array();
	/** @var Mongo */
	public $connection = null;

	/**
	 * @param string $dsn
	 * @param array $options
	 */
	public function __construct($dsn, array $options = array()) {
		$this->dsn = $dsn;
		$this->options = $options;

		extract(parse_url($this->dsn)); // FIXME parse_url can corrupt UTF-8 string

		/**
		 * @var string $scheme
		 * @var string $port
		 * @var string $host
		 * @var string $user
		 * @var string $pass
		 * @var string $path
		 */
		$this->scheme = isset($scheme) ? $scheme : null;
		$this->port = isset($port) ? $port : null;
		$this->host = isset($host) ? $host : null;
		$this->user = isset($user) ? $user : null;
		$this->pass = isset($pass) ? $pass : null;
		$this->dbname = isset($path) ? substr($path, 1) : null;
	}

	/**
	 * @return null|Mongo
	 */
	public function __invoke() {
		return $this->getConnection();
	}

	/**
	 * @throws \envtesting\Error
	 */
	public function connect() {
		if (!$this->dbname) throw new \envtesting\Warning('Database not selected.');

		try {
			if (!class_exists('Mongo')) throw new Error('PHP Mongo support is missing.');
			$this->connection = new \Mongo($this->dsn, $this->options);
		} catch (\MongoConnectionException $e) {
			throw new Error('Connection failed: ' . $e->getMessage());
		}
	}

	/**
	 * @return null|Mongo
	 */
	public function getConnection() {
		if ($this->connection === null) $this->connect();
		return $this->connection;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return preg_replace('#:' . preg_quote($this->pass, '#') . '@#', ':~~~~~@', $this->dsn) . PHP_EOL .
			json_encode($this->options);
	}

}