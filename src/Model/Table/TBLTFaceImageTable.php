<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TBLTFaceImageTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('tblTFaceImage');
        $this->setPrimaryKey('ID');

    }

    public function getImage($card, $key) {
        return $this->find()->where([
            'TimeCardID' => $card,
            'Name LIKE' => "%".$key."%"
        ])->first();
    }
}
