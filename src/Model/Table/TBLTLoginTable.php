<?php

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;

class TBLTLoginTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblTLogin');
        $this->setPrimaryKey('ID');

        $this->belongsTo('TBLMStaff', [
            'className' => 'TBLMStaff',
            'foreignKey' => 'StaffID',
            'propertyName' => 'TBLMStaff',
        ]);

        $this->belongsTo('TBLMPage', [
            'className' => 'TBLMPage',
            'foreignKey' => 'PageID',
            'propertyName' => 'TBLMPage',
        ]);
    }
}
