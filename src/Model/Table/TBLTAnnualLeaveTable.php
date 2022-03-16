<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTAnnualLeaveTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblTAnnualLeave');
		$this->setPrimaryKey('ID');

		$this->belongsTo('TBLMStaff', [
			'className' => 'TBLMStaff',
			'foreignKey' => false,
			'conditions' => ['TBLTAnnualLeave.StaffID = TBLMStaff.StaffID'],
			'propertyName' => 'TBLMStaff',
		]);

		// $this->hasMany('TBLTNotice', [
		// 	'className' => 'TBLTNotice',
		// 	'foreignKey' => false,
		// 	'conditions' => ['TBLTOverTime.ID = TBLTNotice.OverTimeID'],
		// 	'propertyName' => 'TBLTNotice',
		// ]);
	}


	public function checkTime($params)
	{
		if(!$params)
		{
			return 0;
		}
		$query = $this->find()->where([
			'StaffID'	=> 	$params['StaffID'],
			'FromDate <='	=>	$params['ToDate'],
			'ToDate >='	=>	$params['FromDate'],
			'Status !='	=>	2
		]);
		return $query->count();
	}

}
