<?php
namespace tests\services\mysql;
use envtesting\Error;

/**
 * Try connect to mysql with PDO
 *
 * @see http://php.net/manual/en/book.pdo.php
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
	/** @var \PDO */
	public $connection = null;

	/**
	 * @param $dsn
	 * @param array $options
	 */
	public function __construct($dsn, array $options = array()) {
		$this->dsn = $dsn;

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

		$this->options = $options;
	}

	/**
	 * @throws \envtesting\Error
	 * @return void
	 */
	public function __invoke() {
		return $this->getConnection();
	}

	/**
	 * @return null|\PDO
	 */
	protected function getConnection() {
		if ($this->connection === null) $this->connect();
		return $this->connection;
	}

	/**
	 * @throws \envtesting\Error
	 */
	private function connect() {
		// check PDO extension
		if (!extension_loaded('pdo')) throw new Error('PHP extension \'pdo\' is not loaded');
		if (!class_exists('PDO')) throw new Error('PDO classs is missing.');

		// check schema and database name
		if ($this->scheme !== 'mysql') throw new Error('Incorect scheme ' . $this->scheme);
		if ($this->dbname === null || $this->dbname == '' || $this->dbname == '/') throw new Error('No database available in data source name');
		if ($this->dbname === null || $this->host == '') throw new Error('No hostname set in data source name');

		$dsn = $this->scheme . ':host=' . $this->host . ($this->port ? ';port=' . $this->port : null) . ';dbname=' . $this->dbname;

		try {
			$this->connection = new \PDO($dsn, $this->user, $this->pass, $this->options);
		} catch (\PDOException $e) {
			throw new Error('PDOException: ' . $e->getMessage() . ' ' . $this);
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->scheme . '://' . $this->user . ':~~~~~@' . $this->host . ($this->port ? ':' . $this->port : '') . '/' . $this->dbname;
	}

}