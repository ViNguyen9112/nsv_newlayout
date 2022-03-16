<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLMRepDetailTable extends Table {
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setTable('tblMRepDetail');
		$this->setPrimaryKey('ID');
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
    public function getDetailCodes($conds = [], $include_deleted = false)
    {
        array_push($conds, $include_deleted ? [] : ['FlagDelete' => 0]);
        return $this->find('list', [
            'keyField' => 'DetailCode',
            'valueField' => function ($e) {
                return '【' .$e->get('DetailCode'). '】' . $e->get('DeName1');
            }
        ])
            ->where($conds)
            ->order(['DeSortNumber' => 'asc']);
    }

    /**
     * @param $DetailCode
     */
    public function deleteRelated($DetailCode) {

    }
}
