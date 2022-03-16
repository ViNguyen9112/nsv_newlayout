<?php

namespace App\Model\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

class TBLMRepTypeTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMRepType');
		$this->setPrimaryKey('ID');

        $this->hasMany('TBLMRepCategory', [
            'className' => 'TBLMRepCategory',
            'bindingKey' => 'TypeCode',
            'foreignKey' => 'TypeCode',
            'conditions' => [
                'TBLMRepCategory.FlagDelete = 0'
            ],
            'propertyName' => 'TBLMRepCategory',
        ]);
	}

    /**
     * @param array $conditions
     * @param array $orders
     * @return array|\Cake\ORM\Query
     */
    public function getReports($conditions = [], $orders = [])
    {
        array_push($conditions, ['FlagDelete' => 0]);
        return $this->find()->where($conditions)->order($orders);
    }
    public function getReportsAdmin($conditions = [], $orders = [])
    {
        return $this->find()->where($conditions)->order($orders);
    }

    /**
     * @param array $conds
     * @param bool $include_deleted
     * @return \Cake\ORM\Query
     */
    public function getTypeCodes($conds = [], $include_deleted = false)
    {
        array_push($conds, $include_deleted ? [] : ['FlagDelete' => 0]);
        return $this->find('list', [
            'keyField' => 'TypeCode',
            'valueField' => function ($e) {
                return '【' .$e->get('TypeCode'). '】' . $e->get('Type1');
            }
        ])
            ->where($conds)
            ->order(['RepSortNumber' => 'asc']);
    }

    /**
     * @param $field
     * @return \Cake\ORM\Query
     */
    public function getAll($field)
    {
        $conds = ['FlagDelete' => 0];
        return $this->find('list', [
            'keyField' => 'TypeCode',
            'valueField' => $field
        ])
            ->where($conds)
            ->order(['RepSortNumber' => 'asc']);
    }

    /**
     * @param $TypeCode
     * @return \Cake\ORM\Query
     */
    public function getReportBy($TypeCode)
    {
        $conds = [
            'TBLMRepType.FlagDelete' => 0,
            'TBLMRepType.TypeCode' => $TypeCode,
        ];
        return $this->find()
            ->select()
            ->contain(['TBLMRepCategory', 'TBLMRepCategory.TBLMRepDetail'])
            ->where($conds)
            ->order([
                'TBLMRepType.RepSortNumber'
            ]);
    }



    /**
     * @param $TypeCode
     */
    public function deleteRelated($TypeCode) {
        $item2UpdateObj = ['FlagDelete' => 1];
        $Categories = TableRegistry::getTableLocator()->get('TBLMRepCategory')
            ->find()
            ->select()
            ->where(['TypeCode' => $TypeCode]);
        $result_cate = TableRegistry::getTableLocator()->get('TBLMRepCategory')
            ->updateAll(
                $item2UpdateObj,
                ['TypeCode' => $TypeCode]
            );
        $result_detail = true;
        foreach ($Categories as $key => $value) {
            $result_detail = $result_detail && TableRegistry::getTableLocator()->get('TBLMRepDetail')
            ->updateAll(
                $item2UpdateObj,
                ['CatCode' => $value->CatCode]
            );
            
        }
        return $result_cate && $result_detail;
           
    }
}
