<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMLanguageTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMLanguage');
		$this->setPrimaryKey('KeyString');
	}
}
