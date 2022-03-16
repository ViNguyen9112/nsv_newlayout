<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTNoticealTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblTNoticeal');
        $this->setPrimaryKey('ID');

		$this->belongsTo('TBLMStaff', [
			'className' => 'TBLMStaff',
			'foreignKey' => false,
			'conditions' => ['TBLTNoticeal.StaffID = TBLMStaff.StaffID'],
			'propertyName' => 'TBLMStaff',
		]);

		$this->hasOne('TBLTAnnualLeave', [
			'className' => 'TBLTAnnualLeave',
			'foreignKey' => false,
			'conditions' => ['TBLTAnnualLeave.ID = TBLTNoticeal.AnnualLeaveID'],
			'propertyName' => 'TBLTAnnualLeave',
		]);
    }

	public function getAnnualLeaveRequest($conditions = [], $orders = [])
    {
        return empty($conditions) ? $this->find()->contain(['TBLTAnnualLeave', 'TBLMStaff']) : $this->find()->contain(['TBLTAnnualLeave', 'TBLMStaff'])->where($conditions)->order($orders);
    }
}
