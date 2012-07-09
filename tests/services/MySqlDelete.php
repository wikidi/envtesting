<?php
namespace tests\services;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MySqlDelete extends MySqlConnection {

	/** @var string */
	public $table = '_envtesting_';

	/**
	 * @param string $dsn
	 * @param array $options
	 * @param string $table
	 */
	public function __construct($dsn, array $options = array(), $table = '_envtesting_') {
		parent::__construct($dsn, $options);
		$this->table = $table;
	}

	/**
	 * @throws \envtesting\Error
	 */
	public function __invoke() {
		parent::__invoke(); // connect to mysql using \PDO
		$sql = 'DELETE FROM ' . $this->table . ' WHERE data = "testDelete"';
		try {
			$rowCount = $this->connection->exec($sql);
			$info = $this->connection->errorInfo();

			if ($rowCount != 1 && $info[0] == '00000') {
				throw new \envtesting\Error('Delete operation failed: No data found.');
			} else if ($info[0] != '00000') {
				throw new \envtesting\Error('Delete operation failed! Error #no: ' . $info[0] . ' (' . $info[2] . ')');
			}

		} catch (\PDOException $e) {
			throw new \envtesting\Error('Delete operation failed: ' . $e->getMessage());
		}

	}

}