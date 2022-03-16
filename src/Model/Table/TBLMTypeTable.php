<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMTypeTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMType');
		$this->setPrimaryKey('TypeCode');

		$this->hasMany('TBLMCheck', [
			'className' => 'TBLMCheck',
			'foreignKey' => false,
			'conditions' => ['TBLMCheck.TypeCode = TBLMType.TypeCode'],
			'propertyName' => 'TBLMCheck',
        ]);

        $this->hasMany('TBLTTimeCard', [
			'className' => 'TBLTTimeCard',
			'foreignKey' => false,
			'conditions' => ['TBLTTimeCard.TypeCode = TBLMType.TypeCode'],
			'propertyName' => 'TBLTTimeCard',
        ]);
	}
}
