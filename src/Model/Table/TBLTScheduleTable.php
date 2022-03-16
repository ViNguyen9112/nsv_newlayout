<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTScheduleTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblTSchedule');
		$this->setPrimaryKey('ID');
    }
}
