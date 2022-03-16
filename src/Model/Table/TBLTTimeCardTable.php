<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTTimeCardTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblTTimeCard');
		$this->setPrimaryKey('TimeCardID');

		$this->belongsTo('TBLMCustomer', [
            'className' => 'TBLMCustomer',
            'foreignKey' => false,
            'conditions' => ['TBLTTimeCard.CustomerID = TBLMCustomer.CustomerID'],
            'propertyName' => 'TBLMCustomer',
	   ]);

		$this->belongsTo('TBLMStaff', [
            'className' => 'TBLMStaff',
            'foreignKey' => false,
            'conditions' => ['TBLTTimeCard.StaffID = TBLMStaff.StaffID'],
            'propertyName' => 'Tblmstaff',
	   ]);

	   $this->hasOne('TBLTReport', [
			'className' => 'TBLTReport',
			'foreignKey' => false,
			'conditions' => ['TBLTTimeCard.TimeCardID = TBLTReport.TimeCardID'],
			'propertyName' => 'TBLTReport',
        ]);

        // $this->hasMany('TBLTFaceImage', [
		// 	'className' => 'TBLTFaceImage',
		// 	'foreignKey' => 'TimeCardID',
		// 	'propertyName' => 'TBLTFaceImage',
		// ]);
	}


	public function getStaffTime($id,$time){
		$time=explode('/',$time);
		return  $this->find()->contain(['TBLMCustomer','TBLTReport'])
				->where([
					'StaffID'=> $id,
					'MONTH(Created_at)'=>$time[0],
					'YEAR(Created_at)'=>$time[1],
				]);
	}


    /**
     * @return array|\Cake\ORM\Query
     */
	public function getVisits($conditions = [], $order = []) {
        $query = $this->query();
        return $this->find()
            ->contain(['TBLMCustomer', 'TBLMStaff'])
            ->select([
                'TimeCardID' => 'TBLTTimeCard.TimeCardID',
                'CustomerID' => 'TBLTTimeCard.CustomerID',
                'CustomerName' => 'TBLMCustomer.Name',
                'StaffID' => 'TBLMStaff.StaffID',
                'StaffName' => 'TBLMStaff.Name',
                'LastVisit' => $query->func()->max('TBLTTimeCard.Date'),
            ])
            ->where($conditions)
            ->group(['CustomerID'])
            ->order($order);
    }

    /**
     * @return array|\Cake\ORM\Query
     */
	public function getHistoris($conditions, $orders = []) {
        return $this->find()
            ->contain(['TBLMStaff', 'TBLTReport', 'TBLMCustomer'])
            ->select()
            ->where($conditions)->order($orders);
    }

    /**
     * @return \Cake\ORM\Query
     */
    public function getTimeVistis()
    {
        return $this->find('all');
    }

}
