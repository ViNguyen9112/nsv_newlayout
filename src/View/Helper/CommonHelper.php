<?php
/**
 * Created by PhpStorm.
 * User: Thuy
 * Date: 2017/01/04
 * Time: 10:29
 */

namespace App\View\Helper;

use App\Controller\Component\CommonComponent;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\View\Helper;
use Constants;

class CommonHelper extends Helper
{
    /**
     * @var CommonComponent
     */
    private $Common;

    public function initialize(array $config)
    {
        $this->Common = new CommonComponent(new ComponentRegistry());
    }

    /**
     * @param $position
     * @return bool
     */
    public function isSupper($position) {
        return $this->Common->isSupper($position);
    }

    /**
     * @param $position
     * @return bool
     */
    public function isAdmin($position) {
        return $this->Common->isAdmin($position);
    }

    /**
     * @param $position
     * @return bool
     */
    public function isSupperLeader($position) {
        return $this->Common->isSupperLeader($position);
    }

    /**
     * @param $position
     * @return bool
     */
    public function isLeader($position) {
        return $this->Common->isLeader($position);
    }

    /**
     * @param bool $include_delete
     * @return bool
     */
    public function getAreaByPosition($include_delete = false) {
        $StaffID = $this->request->getSession()->read('Auth.Admin.StaffID');
        $staffTable = TableRegistry::getTableLocator()->get('TBLMStaff');
        $user = $staffTable->find()->where(['StaffID' => $StaffID])->first();       
        $regionTable = TableRegistry::getTableLocator()->get('TBLMRegion');
        $areas = $regionTable->getRegionIDs($include_delete);
        if ($this->isSupper($user->Position) === false) {
            $areas = [
                $user->Region => $areas->toArray()[$user->Region]
            ];
        }
        return $areas;
    }

    /**
     * @param $position
     * @return bool
     */
    public function getPositionByLogin() {
        $StaffID = $this->request->getSession()->read('Auth.Admin.StaffID');
        $staffTable = TableRegistry::getTableLocator()->get('TBLMStaff');
        $user = $staffTable->find()->where(['StaffID' => $StaffID])->first();
        $positions = array_merge(Constants::$positions['supper_admin'], Constants::$positions['admin'],  Constants::$positions['supper_leader'],  Constants::$positions['leader']);
        if ($this->isAdmin($user->Position)) {
            $positions = array_merge(\Constants::$positions['admin'],  Constants::$positions['supper_leader'],  Constants::$positions['leader']);
        }
        elseif ($this->isSupperLeader($user->Position)) {
            $positions = array_merge(Constants::$positions['supper_leader'],  Constants::$positions['leader']);
        }
        elseif ($this->isLeader($user->Position)) {
            $positions = array_merge(Constants::$positions['leader']);
        }
        return $positions;
    }
}
