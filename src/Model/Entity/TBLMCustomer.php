<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
/**
 * Tblmstaff Entity
 *
 * @property string $StaffID
 * @property string $Name
 * @property string $Password
 * @property int $Admin
 * @property \Cake\I18n\FrozenTime $Created_at
 */
class TBLMCustomer extends Entity
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
        'CustomerID' => true,
        'Name' => true,
        'AreaID' => true,
        'Address' => true,
        'ImplementDate' => true,
        'PositionNo' => true,
        'Latitude' => true,
        'Longitude' => true,
        'TaxCode' => true,
    ];

    protected $_hidden = [
        'Password'
    ];
}
