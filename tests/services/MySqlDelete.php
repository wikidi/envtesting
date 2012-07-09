<?php
namespace tests\services;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MySqlDelete extends MySqlConnection {

	/** @var string */
	private $table = '_envtesting_';

	/**
	 * @throws \envtesting\Error
	 */
	public function __invoke($table = '_envtesting_') {
		$this->table = $table;
		try {
			$sql = 'DELETE FROM ' . $this->table . ' WHERE data = "testDelete";';

			$rowCount = $this->getConnection()->exec($sql);
			$info = $this->connection->errorInfo();

			if ($rowCount != 1 && $info[0] == '00000') {
				throw new \envtesting\Error(' Delete operation failed: No data found. ' . $this);
			} else if ($info[0] != '00000') {
				throw new \envtesting\Error('Delete operation failed! Error #no: ' . $info[0] . ' (' . $info[2] . ') ' . $this);
			}
		} catch (\PDOException $e) {
			throw new \envtesting\Error('Delete operation failed: ' . $e->getMessage());
		}

		$sql = 'INSERT INTO ' . $this->table . ' VALUES ("testDelete");';
		try {
			$this->getConnection()->exec($sql);
		} catch (\PDOException $e) {
			throw new \envtesting\Error('Insert operation failed (clean up): ' . $e->getMessage());
		}
	}
}