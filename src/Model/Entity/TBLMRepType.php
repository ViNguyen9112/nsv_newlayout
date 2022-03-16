<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
/**
 * TBLMRepType Entity
 *
 */
class TBLMRepType extends Entity
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
        'TypeVN',
        'TypeEN',
        'TypeJP',
        'Icon'
    ];

    /**
     * @return string
     */
    protected function _getTypeVN() {
        return $this->Type1;
    }

    /**
     * @return string
     */
    protected function _getTypeEN() {
        return $this->Type2;
    }

    /**
     * @return string
     */
    protected function _getTypeJP() {
        return $this->Type3;
    }

     protected function _getIcon() {
        return (int)$this->TypeCode;
    }
}
