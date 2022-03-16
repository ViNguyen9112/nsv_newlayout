<?php

namespace App\Model\Table;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

class TBLMRepCategoryTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMRepCategory');
		$this->setPrimaryKey('ID');

        $this->hasMany('TBLMRepDetail', [
            'className' => 'TBLMRepDetail',
            'bindingKey' => 'CatCode',
            'foreignKey' => 'CatCode',
            'conditions' => [
                'TBLMRepDetail.FlagDelete = 0'
            ],
            'propertyName' => 'TBLMRepDetail',
        ]);
        $this->belongsTo('TBLMRepType')
            ->setForeignKey(false)
            ->setJoinType('LEFT')
            ->setConditions([
                'TBLMRepType.TypeCode = TBLMRepCategor.TypeCode',
            ])
            ->setProperty('TBLMRepType');
	}

    /**
     * @param array $conditions
     * @param array $orders
     * @return array|\Cake\ORM\Query
     */
    public function getCategories($conditions = [], $orders = [])
    {
        array_push($conditions, ['FlagDelete' => 0]);
        array_push($conditions, ['HideFlag' => 0]);
        return $this->find()->where($conditions)->order($orders);
    }
    public function getCategoriesAdmin($conditions = [], $orders = [])
    {
        return $this->find()->where($conditions)->order($orders);
    }

    /**
     * @param array $conds
     * @param bool $include_deleted
     * @return \Cake\ORM\Query
     */
    public function getCatCodes($conds = [], $include_deleted = false)
    {
        array_push($conds, $include_deleted ? [] : ['FlagDelete' => 0]);
        return $this->find('list', [
            'keyField' => 'CatCode',
            'valueField' => function ($e) {
                return '【' .$e->get('CatCode'). '】' . $e->get('CatName1');
            }
        ])
            ->where($conds)
            ->order(['CatSortNumber' => 'asc']);
    }

    /**
     * @param $TypeCode
     */
    public function deleteRelated($CatCode) {
        $item2UpdateObj = ['FlagDelete' => 1]; 
        $result_detail =  TableRegistry::getTableLocator()->get('TBLMRepDetail')
        ->updateAll(
            $item2UpdateObj,
            ['CatCode' => $CatCode]
        );
        return $result_detail;           
    }
}
