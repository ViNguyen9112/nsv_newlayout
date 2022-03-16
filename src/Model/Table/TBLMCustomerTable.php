<?php

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;

class TBLMCustomerTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblMCustomer');
        $this->setPrimaryKey('ID');

        $this->belongsTo('TBLMArea', [
            'className' => 'TBLMArea',
            'foreignKey' => 'AreaID',
            'propertyName' => 'TBLMArea',
        ]);

        $this->hasMany('TBLTTimeCard', [
            'className' => 'TBLTTimeCard',
            'foreignKey' => false,
            'conditions' => ['TBLMCustomer.CustomerID = TBLTTimeCard.CustomerID'],
            'propertyName' => 'TBLTTimeCard',
        ]);
    }

    /**
     * @param array $conds
     * @return array
     */
    public function getAllCustomer($conds = []) {
        array_push($conds, ['FlagDelete' => 0]);
        $staff=$this->find()->select(['CustomerID','Name'])->where($conds);
        $result=[];
        foreach($staff as $key =>$value){
            $id=$value['CustomerID'];
            $result[$id]=$value['CustomerID'].'-'.$value['Name'];
        }
        return $result;
    }

    /**
     * @return array|\Cake\ORM\Query
     */
    public function getCustomers($conditions = [], $orders = [])
    {
        array_push($conditions, ['FlagDelete' => 0]);
        return empty($conditions) ? $this->find()->contain(['TBLMArea']) :
            $this->find()->contain(['TBLMArea'])->where($conditions)->order($orders);
    }

    /**
     * @return array|\Cake\ORM\Query
     */
    public function getCustomer($id)
    {
        return $this->find()->contain(['TBLMArea'])->where(['TBLMCustomer.ID' => $id])->first();
    }

    /**
     * @return array|EntityInterface
     */
    public function getCustomerIDs()
    {
        return $this->find('list', [
            'keyField' => 'CustomerID',
            'valueField' => "CustomerID"
        ])
            ->where([
                'FlagDelete' => 0
            ]);
    }

    public function findByCustomerID($customerID){
        return $this->find()
            ->contain('TBLMArea')
            ->select([
                'ID' => 'TBLMCustomer.ID',
                'CustomerID' => 'TBLMCustomer.CustomerID',
                'Name' => 'TBLMCustomer.Name',
                'AreaID' => 'TBLMArea.AreaID',
                'AreaName' => 'TBLMArea.Name',
                'Address' => 'TBLMCustomer.Address',
                'Latitude' => 'TBLMCustomer.Latitude',
                'Longitude' => 'TBLMCustomer.Longitude',
                'TaxCode' => 'TBLMCustomer.TaxCode',
                'ImplementDate' => 'TBLMCustomer.ImplementDate',
                'PositionNo' => 'TBLMCustomer.PositionNo',
            ])
            ->where([
                'CustomerID' => $customerID,
                'FlagDelete' => 0
            ])
            ->first();
    }
}
