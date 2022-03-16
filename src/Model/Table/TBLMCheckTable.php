<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMCheckTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMCheck');
		$this->setPrimaryKey('CheckID');

		$this->belongsTo('TBLMType', [
			'className' => 'TBLMType',
			'foreignKey' => false,
			'conditions' => ['TBLMType.TypeCode = TBLMCheck.TypeCode'],
			'propertyName' => 'TBLMType',
        ]);
        $this->belongsTo('TBLMCategory', [
			'className' => 'TBLMCategory',
			'foreignKey' => false,
			'conditions' => ['TBLMCategory.CategoryCode = TBLMCheck.CategoryCode'],
			'propertyName' => 'TBLMCategory',
		]);
	}
}
