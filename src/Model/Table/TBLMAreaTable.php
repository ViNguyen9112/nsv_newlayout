<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class TBLMAreaTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMArea');
		$this->setPrimaryKey('AreaID');

		$this->hasMany('TBLTCustomer', [
			'className' => 'TBLTCustomer',
			'foreignKey' => false,
			'conditions' => ['TBLTCustomer.AreaID = TBLMArea.AreaID'],
			'propertyName' => 'TBLTCustomer',
        ]);

        $this->hasMany('TBLMCustomer', [
            'className' => 'TBLMCustomer',
            'bindingKey' => 'AreaID',
            'foreignKey' => 'AreaID',
            'propertyName' => 'TBLMCustomer',
        ]);

        $this->hasOne('TBLMAreaStaff', [
			'className' => 'TBLMAreaStaff',
			'foreignKey' => false,
			'conditions' => ['TBLMArea.AreaID = TBLMAreaStaff.AreaID'],
			'propertyName' => 'TBLMAreaStaff',
        ]);
    }

    public function getAreaIDs()
    {
        return $this->find('list', [
            'keyField' => 'AreaID',
            'valueField' => function ($e) {
                return '【' .$e->get('AreaID'). '】' . $e->get('Name');
            }
        ])->order(['Name' => 'asc']);
    }

    public function getAreaIDsByRegion($region)
    {
        return $this->find('list', [
            'keyField' => 'AreaID',
            'valueField' => function ($e) {
                return '【' .$e->get('AreaID'). '】' . $e->get('Name');
            }
        ])
            ->where([
                'Region' => $region
            ])
            ->order(['Name' => 'asc']);
    }

	public function getAreaIDsByRegionNew($region)
    {
        return $this->find('list', [
            'keyField' => 'AreaID',
            'valueField' => "AreaID"
        ])
            ->where([
                'Region' => $region
            ])
            ->order(['Name' => 'asc']);
    }

    public function getAreaIDsByConds($conds)
    {
        array_push($conds, ['Deleted' => 0]);
        return $this->find('list', [
            'keyField' => 'AreaID',
            'valueField' => function ($e) {
                return '【' .$e->get('AreaID'). '】' . $e->get('Name');
            }
        ])
            ->where($conds)
            ->order(['Name' => 'asc']);
    }

	public function findByAreaID($areaId){
		return $this->find()->where(["AreaID" => $areaId])->first();
	}

	public function getAlls($conds){
        array_push($conds, ['Deleted' => 0]);
		return $this->find()->where([$conds])->order('Name','ASC')->toArray();
	}

	#Deleted all
    public function deleteRelated($areaID) {
	    #Customers
        $this->TBLMCustomerObj = TableRegistry::getTableLocator()->get('TBLMCustomer');
        $this->TBLMCustomerObj->updateAll(
            [  // fields
                'FlagDelete' => 1,
            ],
            [  // conditions
                'AreaID' => $areaID
            ]
        );

	    #TBLMAreaStaff
        $this->TBLMAreaStaffObj = TableRegistry::getTableLocator()->get('TBLMAreaStaff');
        $this->TBLMAreaStaffObj->updateAll(
            [  // fields
                'Deleted' => 1,
            ],
            [  // conditions
                'AreaID' => $areaID
            ]
        );
    }
}
