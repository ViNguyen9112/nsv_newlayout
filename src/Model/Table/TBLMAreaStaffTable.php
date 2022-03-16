<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMAreaStaffTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblMAreaStaff');
        $this->setPrimaryKey('ID');

        $this->belongsTo('TBLMStaff', [
            'className' => 'TBLMStaff',
            'foreignKey' => false,
            'conditions' => ['TBLMStaff.StaffID = TBLMAreaStaff.StaffID'],
            'propertyName' => 'TBLMStaff',
        ]);

        $this->belongsTo('TBLMArea', [
            'className' => 'TBLMArea',
            'foreignKey' => false,
            'conditions' => ['TBLMArea.AreaID = TBLMAreaStaff.AreaID'],
            'propertyName' => 'TBLMArea',
        ]);
    }

    /**
     * @param $StaffID
     * @return array
     */
    public function getAreaManager($StaffID)
    {
        $areas = $this->find()->where(['StaffID' => $StaffID])->toArray();
        $arr_areas = [];
        foreach ($areas as $area) {
            array_push($arr_areas, $area->AreaID);
        }

        return $arr_areas;
    }

    /**
     * @param $StaffID
     * @return array
     */
    public function getStaffManager($areas)
    {
        $areas = $this->find()->where(['AreaID IN' => $areas])->group('StaffID')->toArray();
        $arr_areas = [];
        foreach ($areas as $area) {
            array_push($arr_areas, $area->StaffID);
        }

        return $arr_areas;
    }

    public function getAreaOfStaff($StaffID) {
        return $this->find()
            ->contain('TBLMArea')
            ->where(['StaffID' => $StaffID, 'TBLMAreaStaff.Deleted' => 0])
            ->order(['TBLMArea.Name'])
            ->toArray();
    }
}
