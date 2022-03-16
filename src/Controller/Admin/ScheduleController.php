<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Admin;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

use Cake\Event\Event;
use Cake\Log\Log;

class ScheduleController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMArea');
        $this->loadModel('TBLMCustomer');
        $this->loadModel('TBLMCategory');
        $this->loadModel('TBLTCheckResult');
        $this->loadModel('TBLTImageReport');
        $this->loadModel('TBLTReport');
        $this->loadModel('TBLTTimeCard');
        $this->loadModel('TBLMAreaStaff');
        $this->loadModel('TBLMStaff');
        $this->loadModel('TBLMRegion');
        $this->loadModel('TBLMRepType');

        $this->viewBuilder()->setLayout('admin');
        $this->set('roll', 'admin');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    /**
     *
     */
    public function index()
    {
        $staffId = $this->Auth->user('StaffID');
        $this->set('StaffID', $staffId);

        $params['datepicker'] = date('Y/m/d');
        $this->set('params', $params);

        $conds = [];
        $arr_areas = $this->Common->chkAreas();
        if (!empty($arr_areas)) {
            $conds = ['AreaID IN' => $arr_areas];
        }
        $listArea = $this->TBLMArea->find()->where([$conds])->order('Name','ASC')->toArray();
        $this->set('listArea', $listArea);

        $customerIds = $this->TBLMCustomer->getAllCustomer($conds);
        $this->set('customerIds', $customerIds);

        $conds = [];
        $user = $this->TBLMStaff->find()->where(['StaffID' => $staffId])->first();
        if ($this->Common->isSupperLeader($user->Position)) {
            $arr_staffs = $this->TBLMAreaStaff->getStaffManager($arr_areas);
            if (!empty($arr_staffs)) {
                $conds = ['StaffID IN' => $arr_staffs];
            }
        }
        if ($this->Common->isAdmin($user->Position)) {
            $conds = ['Region' => $user->Region];
        }
        $staffIds = $this->TBLMStaff->getStaffsUser($conds);
        $this->set('staffIds', $staffIds);
    }

    public function getReport(){
        $id = $this->getRequest()->getData('id');
        $response = [];
        $response['report'] = $this->TBLTReport->getReport($id);
        $timecardID = $this->getRequest()->getData('timecardID');

        if($response['report'] === NULL){
            $response['timecard'] = $this->TBLTTimeCard->find()
                ->where(['TimeCardID' => $timecardID])
                ->select([
                    'ftime' => "CONCAT(DATE_FORMAT(TimeIn, '%H:%i:%s'),'', CASE WHEN TimeOut IS NOT NULL THEN CONCAT(' 〜 ', DATE_FORMAT(TimeOut, '%H:%i:%s')) ELSE '' END)"
                ])
                ->first();
        } else {
            $checks = $this->TBLMRepType->getReportBy($response['report']->TypeCode);

            $response['formCheck'] = false;
            // if have checkbox -> get form
            $idxLanguage = \Constants::$languages['admin_key_form']['col'];
            if(!empty($checks)){
                $response['formCheck'] = true;
                foreach($checks as $check){
                    $categories = $check->TBLMRepCategory;
                    foreach ($categories as $category) {
                        $idCat = $category->ID;
                        $details = $category->TBLMRepDetail;
                        $TypeOfCate = $category->TypeCat;
                        $col = "CatName{$idxLanguage}";
                        $Category = $category->$col;
                        foreach ($details as $detail) {
                            $item = [
                                'CheckID' => $detail->ID,
                                'TypeCode' => $check->TypeCode,
                                'CheckCode' => $detail->DetailCode,
                                'TypeOfCate' => $TypeOfCate,
                                'idCat' => $idCat
                            ];
                            foreach (\Constants::$languages['admin_report_idx'] as $k => $v) {
                                $c_col = "CatName{$v}";
                                $chk_col = "DeName{$v}";
                                $item["Category{$k}"] = $category->$c_col;
                                $item["CheckPoint{$k}"] = $detail->$chk_col;

                            }
                            $response['form'][$Category][] = $item;
                        }
                    }
                }

                // get checked
                $response['report']['checked'] = $this->TBLTCheckResult->find()->where(['TimeCardID' => $response['report']->TimeCardID]);
            }

            // get images
            $images = $this->TBLTImageReport->find()->where(['ReportID' => $id]);
            $clone = array();
            foreach ($images as $image) {
                $image['deleted'] = 0;
                $file = "ID_".$image['ReportID']."/".$image['ImageName'];
                if (!file_exists(WWW_ROOT."ImageReport/".$file)) {
                    $image['deleted'] = 1;
                }
                $clone[] =  $image;
            }
            $response['images'] = $clone;
        }
        return $this->response->withType("application/json")->withStringBody(json_encode($response));
    }

    public function getStaff(){
        $result['success'] = 0;
        $staffID = $this->getRequest()->getData('staffID');
        $staff = $this->TBLMStaff->findByStaffID($staffID);
        $data = $staff->toArray();
        $data['AreaName'] = "";

        // get areas
        $areas = $this->TBLMAreaStaff->find()
            ->contain('TBLMArea')
            ->where(['StaffID' => $staff->StaffID])
            ->order(['TBLMArea.Name'])
            ->toArray();

        $last = (key(array_slice($areas, -1, 1, true)));
        foreach($areas as $index=>$area){
            $area_name = $this->TBLMArea->find()->where(['AreaID' => $area->AreaID])->first();
            if($index != $last){
                $data['AreaName'] .= $area_name->Name . ", ";
            } else {
                $data['AreaName'] .= $area_name->Name;
            }
        }

        // get region South, North, Middle
        $NAME_REGION = $this->TBLMRegion->getRegionIDs()->toArray();
        $data['RegionName'] = @$NAME_REGION[$data['Region']];
        $result = [
            'success' => 1,
            'data' => $data
        ];
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function getCustomer(){
        $result['success'] = 0;
        $customerID = $this->getRequest()->getData('customerID');
        $customer = $this->TBLMCustomer->findByCustomerID($customerID);
        $result = [
            'success' => 1,
            'data' => $customer
        ];
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function getArea(){
        $result['success'] = 0;
        $region = $this->getRequest()->getData('region');
        $area = $this->TBLMArea->find()->where(['Region' => $region])->order(['Name' => 'ASC']);
        return $this->response->withType('application/json')->withStringBody(json_encode($area));
    }

    public function sessionSort(){
        $col = $this->getRequest()->getData('col');
        $dir = $this->getRequest()->getData('dir');
        $this->request->session()->write('Config.sort.col', $col);
        $this->request->session()->write('Config.sort.dir', $dir);
        $response = [
            'result' => 'success',
        ];
        return $this->response->withType('application/json')->withStringBody(json_encode($response));
    }
}
