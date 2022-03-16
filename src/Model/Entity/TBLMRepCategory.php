<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Constants;
/**
 * TBLMRepCategory Entity
 *
 */
class TBLMRepCategory extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
    ];

    protected $_virtual = [
        'CategoryVN',
        'CategoryEN',
        'CategoryJP',
        'TypeWithName',
        'TypeCategory',
        'TypeCat'
    ];

    /**
     * @return string
     */
    protected function _getCategoryVN() {
        return $this->CatName1;
    }

    /**
     * @return string
     */
    protected function _getCategoryEN() {
        return $this->CatName2;
    }

    /**
     * @return string
     */
    protected function _getCategoryJP() {
        return $this->CatName3;
    }
     /**
     * @return string
     */
    protected function _getTypeWithName() {
        if(!isset($this->TypeCode)){
            return '';
        }
        if (!empty($this->TBLMRepType)) {
            return '【' .$this->TypeCode. '】'. $this->TBLMRepType->Type1;
        }else{
             $Type = TableRegistry::getTableLocator()->get('TBLMRepType')
                ->find()
                ->select(['Type1' => 'Type1'])
                ->where(['TypeCode' => $this->TypeCode])
                ->first()
                ->Type1;
            return '【' .$this->TypeCode. '】'. $Type ;
        }
        
       
    }
    protected function _getTypeCategory(){
         if (!empty($this->TypeCat)) {
            return Constants::$category_type[$this->TypeCat];
        }
        return "";
    }
}
