<?php
namespace envtests\application\mongo;
/**
 * Check basic mongo operations
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Operations extends \envtests\services\mongo\Connection {
	/** @var string */
	public $collection = '_envtesting_';

	/**
	 * Check insert, find and remove capabilities
	 *
	 * @throws \envtesting\Error
	 */
	public function __invoke() {
		$data = array('test' => uniqid());
		try {

			// INSERT
			$this->getCollection()->insert($data);

			// FIND
			$saved = $this->getCollection()->findOne($data);
			if ($saved === null) {
				throw new \envtesting\Error('Mongo data not saved!' . PHP_EOL . $this);
			}

			// REMOVE
			$this->getCollection()->remove($data);
			$deleted = $this->getCollection()->findOne($data);
			if ($deleted !== null) {
				throw new \envtesting\Error('Data saved, but not removed!' . PHP_EOL . $this);
			}

		} catch (\MongoException $e) {
			throw new \envtesting\Error('Operation failed: ' . $e->getMessage() . PHP_EOL . $this);
		}
	}

	/**
	 * @return \MongoCollection
	 */
	private function getCollection() {
		return $this->getConnection()->{$this->dbname}->{$this->collection};
	}
}