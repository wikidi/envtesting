<?php
namespace tests\application\mongo;
/**
 * Check commons mongo operations
 *
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Operations extends \tests\services\mongo\Connection {
	/** @var string */
	public $collection = '_envtesting_';

	public function insertAllow() {

	}

	public function findAllow() {

	}

	public function deleteAllow() {

	}


	private function getCollection() {
		//$database =  ?
//$this->getConnection()->{$this->dbname}->
	}
	/*
 $testData = array('test' => 'test record');
	 //test insert
 $collection = $this->connection->{$this->data['database']}->{$this->data['collection']};
 $collection->insert($testData);
 $dbData = $collection->findOne($testData);
 if ($dbData['test'] != $testData['test']) {
	 throw new \envtesting\exceptions\Exception('Data not saved!');
 }
 //remove
 $collection->remove($testData);
 //test if removed
 $dbData = $collection->findOne($testData);
 if ($dbData['test'] === $testData['test']) {
	 throw new \envtesting\exceptions\Exception('Data saved, but not removed!');
 }
 */
}