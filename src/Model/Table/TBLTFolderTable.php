<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTFolderTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblTFolder');
        $this->setPrimaryKey('ID');

        $this->belongsTo('TBLMStaff', [
            'className' => 'TBLMStaff',
            'foreignKey' => false,
            'conditions' => ['TBLTFolder.Name = TBLMStaff.StaffID'],
            'propertyName' => 'Tblmstaff',
	   ]);
    }
}
