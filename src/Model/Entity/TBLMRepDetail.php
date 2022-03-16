<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
/**
 * TBLMRepDetail Entity
 *
 */
class TBLMRepDetail extends Entity
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
        'CategoryWithName'
    ];
     /**
     * @return string
     */
    protected function _getCategoryWithName() {
        
        $Type = TableRegistry::getTableLocator()->get('TBLMRepCategory')
            ->find()
            ->select(['CatName1' => 'CatName1'])
            ->where(['CatCode' => $this->CatCode])
            ->first()
            ->CatName1;
        return '【' .$this->CatCode. '】'. $Type ;        
    }
}
