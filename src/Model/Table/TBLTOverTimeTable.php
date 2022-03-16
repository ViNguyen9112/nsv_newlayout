<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTOverTimeTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblTOverTime');
		$this->setPrimaryKey('ID');

		$this->belongsTo('TBLMStaff', [
			'className' => 'TBLMStaff',
			'foreignKey' => false,
			'conditions' => ['TBLTOverTime.StaffID = TBLMStaff.StaffID'],
			'propertyName' => 'TBLMStaff',
		]);

		$this->hasMany('TBLTNotice', [
			'className' => 'TBLTNotice',
			'foreignKey' => false,
			'conditions' => ['TBLTOverTime.ID = TBLTNotice.OverTimeID'],
			'propertyName' => 'TBLTNotice',
		]);
	}


	public function checkTime($params)
	{
		if(!$params)
		{
			return 0;
		}
		return $this->find()->where([
			'StaffID'	=> 	$params['StaffID'],
			'StartTime <='	=>	$params['EndTime'],
			'EndTime >='	=>	$params['StartTime'],
			'Status !='	=>	2
		])->count();
	}

}
