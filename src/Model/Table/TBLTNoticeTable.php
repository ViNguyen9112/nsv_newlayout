<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTNoticeTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblTNotice');
        $this->setPrimaryKey('ID');

		$this->belongsTo('TBLMStaff', [
			'className' => 'TBLMStaff',
			'foreignKey' => false,
			'conditions' => ['TBLTNotice.StaffID = TBLMStaff.StaffID'],
			'propertyName' => 'TBLMStaff',
		]);

		$this->hasOne('TBLTOverTime', [
			'className' => 'TBLTOverTime',
			'foreignKey' => false,
			'conditions' => ['TBLTOverTime.ID = TBLTNotice.OverTimeID'],
			'propertyName' => 'TBLTOverTime',
		]);
    }

	public function getOvertimeRequest($conditions = [], $orders = [], $oconditions = [])
    {
        $query = empty($conditions) ? $this->find()->contain(['TBLTOverTime', 'TBLMStaff'])->order($orders) : $this->find()->contain(['TBLTOverTime', 'TBLMStaff'])->where($conditions)->orWhere($oconditions)->order($orders);
		//var_dump($query->sql());
		return $query;
    }
}
