<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTDistanceTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblTDistance');
		$this->setPrimaryKey('ID');

		$this->belongsTo('TBLMStaff', [
			'className' => 'TBLMStaff',
			'foreignKey' => false,
			'conditions' => ['TBLMStaff.StaffID = TBLTDistance.StaffID'],
			'propertyName' => 'TBLMStaff',
        ]);
    }
}
