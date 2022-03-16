<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMCategoryTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMCategory');
		$this->setPrimaryKey('CategoryCode');

		$this->hasMany('TBLMCheck', [
			'className' => 'TBLMCheck',
			'foreignKey' => false,
			'conditions' => ['TBLMCheck.CategoryCode = TBLMType.CategoryCode'],
			'propertyName' => 'TBLMCheck',
        ]);
    }
}
