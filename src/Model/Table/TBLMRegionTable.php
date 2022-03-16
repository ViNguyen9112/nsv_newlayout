<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class TBLMRegionTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblMRegion');
        $this->setPrimaryKey('ID');

        $this->hasMany('TBLMArea', [
            'className' => 'TBLMArea',
            'bindingKey' => 'RegionID',
            'foreignKey' => 'Region',
            'propertyName' => 'TBLMArea',
        ]);

        $this->hasMany('TBLMCustomer', [
            'className' => 'TBLMCustomer',
            'foreignKey' => false,
            'conditions' => ['TBLMRegion.RegionID = TBLMCustomer.Region'],
            'propertyName' => 'TBLMCustomer',
        ]);
    }

    public function getRegionIDs($include_delete = false)
    {
        $conds = $include_delete ? [] : ['Deleted' => 0];
        return $this->find('list', [
            'keyField' => 'RegionID',
            'valueField' => function ($e) {
                return '【' .$e->get('RegionID'). '】' . $e->get('Region');
            }
        ])
            ->where($conds)
            ->order(['Region' => 'asc']);
    }

    public function getRegionIDsByRegion($region)
    {
        return $this->find('list', [
            'keyField' => 'RegionID',
            'valueField' => function ($e) {
                return '【' .$e->get('RegionID'). '】' . $e->get('Region');
            }
        ])
            ->where([
                'Region' => $region,
                'Deleted' => 0
            ])
            ->order(['Name' => 'asc']);
    }

    public function findByRegionID($RegionID)
    {
        return $this->find()->where(["RegionID" => $RegionID])->first();
    }

    #Deleted all
    public function deleteRelated($regionID) {
        #Staff
        $this->TBLMStaffObj = TableRegistry::getTableLocator()->get('TBLMStaff');
        $this->TBLMStaffObj->updateAll(
            [  // fields
                'FlagDelete' => 1,
            ],
            [  // conditions
                'Region' => $regionID
            ]
        );

        #Areas
        $this->TBLMAreaObj = TableRegistry::getTableLocator()->get('TBLMArea');
        $this->TBLMAreaObj->updateAll(
            [  // fields
                'Deleted' => 1,
            ],
            [  // conditions
                'Region' => $regionID
            ]
        );

        $areas = $this->TBLMAreaObj->getAreaIDsByRegion($regionID);
        if ($areas->count()) {
            $areaIDs = array_keys($areas->toArray());

            #Customers
            $this->TBLMCustomerObj = TableRegistry::getTableLocator()->get('TBLMCustomer');
            $this->TBLMCustomerObj->updateAll(
                [  // fields
                    'FlagDelete' => 1,
                ],
                [  // conditions
                    'AreaID IN' => $areaIDs
                ]
            );
        }
    }
}
