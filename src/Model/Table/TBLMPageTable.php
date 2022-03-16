<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMPageTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMPage');
		$this->setPrimaryKey('PageID');

		$this->hasMany('TBLTLogin', [
			'className' => 'TBLTLogin',
			'foreignKey' => false,
			'conditions' => ['TBLTLogin.PageID = TBLMPage.PageID'],
			'propertyName' => 'TBLTLogin',
        ]);
    }
}
