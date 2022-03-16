<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTImageReportTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblTImageReport');
        $this->setPrimaryKey('ID');

        $this->belongsTo('TBLTReport', [
            'className' => 'TBLTReport',
            'foreignKey' => false,
            'conditions' => ['TBLTReport.ReportID = TBLTReport.ID'],
            'propertyName' => 'TBLTReport',
	   ]);

    }
}
