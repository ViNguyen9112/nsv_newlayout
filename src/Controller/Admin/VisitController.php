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

use App\Model\Entity\TBLMCustomer;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;

class VisitController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMArea');
        $this->loadModel('TBLTTimeCard');
        $this->loadModel('TBLMStaff');
        $this->loadModel('TBLMCustomer');
        $this->loadModel('TBLTReport');
        $this->loadModel('TBLMAreaStaff');

        $this->viewBuilder()->setLayout('admin');
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
        if ($this->getRequest()->is('ajax')) {
            $page = ($this->getRequest()->getData('start') / PAGE_LIMIT_EXTENT) + 1;
            $this->paginate = ['page' => $page, 'limit' => PAGE_LIMIT_EXTENT];

            $orders = [
                "",
                'AreaName',
                "IDCustomer",
                "TBLMCustomer.Name",
                'NumberVisit',
                "TBLMStaff.StaffID",
                "TBLMStaff.Name",
                "LastVisit",
            ];
            // change value for order staff
            $column = $orders[$this->getRequest()->getData('order.0.column')];
            if($column == "TBLMStaff.StaffID"){
                $column = 'IDStaff';
            } else if($column == "TBLMStaff.Name"){
                $column = "StaffName";
            }

            $order = [
                $column => $this->getRequest()->getData('order.0.dir')
            ];
            $conditions = [];
            if ($this->getRequest()->getData('sCustomerID')) {
                $conditions["TBLMCustomer.CustomerID LIKE"] = "%".$this->getRequest()->getData('sCustomerID')."%";
            }
            $conditions["TBLTReport.CustomerID !="] = "null";

            // areas by login
            $arr_areas = $this->Common->chkAreas();
            if (!empty($arr_areas)) {
                $conditions['TBLMCustomer.AreaID IN'] = $arr_areas;
            }

            $having = [];
            if ($this->getRequest()->getData('sArea')) {
                $having["AreaName LIKE"] = "%".$this->getRequest()->getData('sArea')."%";
            }
            if ($this->getRequest()->getData('sStaffID')) {
                $having["IDStaff LIKE"] = "%".$this->getRequest()->getData('sStaffID')."%";
            }
            if ($this->getRequest()->getData('sTimeVisit')) {
                $sTime = str_replace('/', '-', $this->getRequest()->getData('sTimeVisit'));
                $having["LastVisit LIKE"] = "%".$sTime."%";
            }
            $query = $this->TBLTReport->getVisits($conditions, $order, $having);

            // var_dump($query);

            $visits = $this->paginate($query);
            $data = $visits->toArray();
            $response = [
                'recordsTotal' => $query->count(),
                'recordsFiltered' => $query->count(),
                'data' => $data
            ];
            return $this->responseJson($response);
        }
        else {
            $conds = [];
            $arr_areas = $this->Common->chkAreas();
            if (!empty($arr_areas)) {
                $conds = ['AreaID IN' => $arr_areas];
            }
            $areas = $this->TBLMArea->find('list', [
                'keyField' => 'AreaID',
                'valueField' => "Name"
            ])->where([$conds])->order('Name','ASC')->toArray();
            $this->set('areas', $areas);
            $customerIds = $this->TBLMCustomer->getCustomerIDs();
            $this->set('customerIds', $customerIds);
            $staffIds = $this->TBLMStaff->getStaffIDs();
            $this->set('staffIds', $staffIds);
            $rst = $this->TBLTReport->getTimeVistis();
            $timeVisits = [];
            foreach ($rst as $each) {
                $timeVisits[$this->Date->makeFormat($each->DateTime, 'Y/m/d')] = $this->Date->makeFormat($each->DateTime, 'Y/m/d');
            }
            $this->set('timeVisits', $timeVisits);
        }
    }

    public function getFirstStaffID(){
        $customerID = $this->getRequest()->getData('customerID');
        $result['data'] = $this->TBLTReport->find()
            ->contain(['TBLMStaff'])
            ->select([
                'StaffID' => 'TBLTReport.StaffID',
                'StaffName' => 'TBLMStaff.Name'
            ])
            ->where(['CustomerID' => $customerID])
            ->order(['Date' => 'DESC'])
            ->first();
            return $this->response->withType("application/json")->withStringBody(json_encode($result));
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
