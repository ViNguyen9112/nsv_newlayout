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

namespace App\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Constants;

/**
 * @property bool|object TBLMAreaStaff
 * @property bool|object TBLTTimeCard
 * @property bool|object TBLTReport
 * @property bool|object TBLMStaff
 * @property bool|object Common
 */
class AjaxController extends Controller
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadModel('TBLTSchedule');
        $this->loadModel('TBLTReport');
        $this->loadModel('TBLMStaff');
        $this->loadModel('TBLTTimeCard');
        $this->loadModel('TBLTFaceImage');
        $this->loadModel('TBLMAreaStaff');
        $this->loadModel('TBLTImageReport');
        $this->loadModel('TBLTDistance');
        $this->loadModel('TBLMArea');


        $this->loadComponent('Date');
        $this->loadComponent('Common');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    public function getAllHoliday()
    {
        //stop render view
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');

        //get paramater
        $params = $this->request->getData();

        //init conditions
        $staffIds = (isset($params['staffIds'])) ? $params['staffIds'] : [];
        $month = (isset($params['month'])) ? $params['month'] : '';
        $conditions = [];
        if ($staffIds) {
            $conditions['StaffID IN'] = $staffIds;
        }
        if ($month) {
            $conditions['Date LIKE'] = "{$month}%";
        }

        $roll = $params['roll'] ? 1 : 0;

        $title = "CONCAT(DATE_FORMAT(TimeIn, '%H') , (select tblMCustomer.CustomerID From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID))";
        if ($roll) {
            $title = "CONCAT(StaffID,' ', DATE_FORMAT(TimeIn, '%H:%i') , ' ', (select Name From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID))";
        }

        //get schedules
        $events = $this->TBLTTimeCard
            ->find()
            ->select([
                'id' => 'TBLTTimeCard.TimeCardID',
                'title' => $title,
                'ftitle' => "CONCAT(DATE_FORMAT(TimeIn, '%H:%i'),'', CASE WHEN TimeOut IS NOT NULL THEN CONCAT(' - ', DATE_FORMAT(TimeOut, '%H:%i')) ELSE '' END, ' ', (select Name From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID))",
                'start' => "CONCAT(DATE_FORMAT(Date, '%Y-%m-%d'), ' ', DATE_FORMAT(TimeIn, '%H:%i:%s'))",
                'end' => "CONCAT(DATE_FORMAT(Date, '%Y-%m-%d'), ' ', DATE_FORMAT(CASE WHEN TimeOut IS NOT NULL THEN TimeOut ELSE ADDTIME(TimeIn, '1') END, '%H:%i:%s'))",
                'starttime' => "DATE_FORMAT(TimeIn, '%H:%i')",
                'endtime' => "DATE_FORMAT(TimeOut, '%H:%i')",
                'long' => "(select Longitude From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID)",
                'lat' => "(select Latitude From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID)",
                'description' => "CONCAT(StaffID,' (', DATE_FORMAT(TimeIn, '%H:%i'), ' ~ ',  DATE_FORMAT(TimeOut, '%H:%i'),') ', (select Name From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID))",
                'StaffID' => 'StaffID',
                'StaffName' => "(select Name From tblMStaff where tblMStaff.StaffID = TBLTTimeCard.StaffID)",
                'CustomerID' => 'TBLTTimeCard.CustomerID',
                'CustomerName' => '(select Name From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID)',
                'Report' => '(select Report From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID)',
                'Report_ID' => '(select tblTReport.ID From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID)',
                'TypeCode' => '(select tblTReport.TypeCode From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID)',
                'imgcheckin' => "(select CONCAT(tblTFaceImage.`Source`, Name) From tblTFaceImage where TBLTTimeCard.TimeCardID = tblTFaceImage.TimeCardID AND tblTFaceImage.NAME LIKE '%IN%')",
                'imgcheckout' => "(select CONCAT(tblTFaceImage.`Source`, Name) From tblTFaceImage where TBLTTimeCard.TimeCardID = tblTFaceImage.TimeCardID AND tblTFaceImage.NAME LIKE '%OUT%')",
                'color' => '(select
                    tblMRepType.TypeColor
                    From tblTReport
                    INNER JOIN tblMRepType ON tblTReport.TypeCode = tblMRepType.TypeCode
                    where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID
                    )',
                'textColor' => '"white"',
            ])
            ->where($conditions)
            ->all();
        echo json_encode($events);
        die();
    }

    public function updateReport()
    {
        //stop render view
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');

        //get paramater
        $params = $this->request->getData();

        $entity = $this->TBLTReport->findById($params['ID'])->first();
        $entity = $this->TBLTReport->patchEntity($entity, $params, ['validate' => false]);
        $this->TBLTReport->save($entity);
        echo json_encode([]);
        die();
    }

    public function getAllLongLatByDate()
    {
        //stop render view
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');

        //get paramater
        $params = $this->request->getData();

        //init conditions
        $customerIds = (isset($params['customerIds'])) ? $params['customerIds'] : [];
        $staffIds = (isset($params['staffIds'])) ? $params['staffIds'] : [];
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];
        $region = @$params['region'];
        $area = @$params['area'];
        $time_from = @$params['time_from'];
        $time_to = @$params['time_to'];

        $conditions = [];

        // case area leader
        $user = $this->TBLMStaff->find()->where(['StaffID' => $params['auth']])->first();
        if ($this->Common->isSupperLeader($user->Position)) {
            $arr_areas = $this->TBLMAreaStaff->getAreaManager($user->StaffID);
            if (!empty($arr_areas)) {
                $conditions['TBLMCustomer.AreaID IN'] = $arr_areas;
            }
        } else if ($this->Common->isAdmin($user->Position)) {
            $arr_areas = $this->TBLMArea->getAreaIDsByRegionNew($user->Region);
            if (!empty($arr_areas)) {
                $conditions['TBLMCustomer.AreaID IN'] = $arr_areas;
            }
        }


        $conditions['TBLMStaff.FlagDelete ='] = 0;

        if (!empty($staffIds)) {
            $conditions['TBLTTimeCard.StaffID IN'] = $staffIds;
        }
        if (!empty($customerIds)) {
            $conditions['TBLTTimeCard.CustomerID IN'] = $customerIds;
        }
        if (!empty($region)) {
            $conditions['TBLMArea.Region IN'] = $region;
        }
        if (!empty($area)) {
            $conditions['TBLMCustomer.AreaID IN'] = $area;
        }
        if (!empty($time_from)) {
            $date_from_format = date('Y-m-d', strtotime($date_from));
            $time_from = "{$date_from_format} {$time_from}";
            $conditions["CONCAT(`Date`, ' ', `TimeIn`) >="] = $time_from;
        }
        if (!empty($time_to)) {
            $date_to_format = date('Y-m-d', strtotime($date_to));
            $time_to = "{$date_to_format} {$time_to}";
            $conditions["CONCAT(`Date`, ' ', `TimeIn`) <="] = $time_to;
        }

        $orders = [
            "",
            "TBLTTimeCard.StaffID",
            "StaffName",
            "checkin",
            "checkout",
            "CustomerName",
            "",
            "",
            "",
            "",
            "TBLMArea.Region",
            "TBLMArea.Name",
        ];
        $order = [
            $orders[$this->getRequest()->getData('order.0.column')] => $this->getRequest()->getData('order.0.dir')
        ];
        $query = $this->TBLTTimeCard
		->find()
		->contain(['TBLMStaff', 'TBLMCustomer', 'TBLMCustomer.TBLMArea'])
		->select([
			'TimecardID' => 'TBLTTimeCard.TimeCardID',
			'id' => "(select tblTReport.ID From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID LIMIT 1)",
			'TypeCode' => "(select tblTReport.TypeCode From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID LIMIT 1)",
			'StaffID' => 'TBLTTimeCard.StaffID',
			'date' => " DATE_FORMAT(Date, '%Y/%m/%d')",
			'time' => "(select DATE_FORMAT(tblTReport.DateTime, '%H:%i:%s') From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID LIMIT 1)",
			'ftime' => "CONCAT(DATE_FORMAT(TimeIn, '%H:%i:%s'),'', CASE WHEN TimeOut IS NOT NULL THEN CONCAT(' 〜 ', DATE_FORMAT(TimeOut, '%H:%i:%s')) ELSE '' END)",
			'CustomerID' => 'TBLTTimeCard.CustomerID',
			'AreaID' => 'TBLMCustomer.AreaID',
			'Report' => "(select Report From tblTReport where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID ORDER BY ID DESC LIMIT 1)",
			'StaffName' => "(select Name From tblMStaff where tblMStaff.StaffID = TBLTTimeCard.StaffID LIMIT 1)",
			'StaffCreatedDate' => "(select DATE_FORMAT(Created_at, '%Y/%m/%d') From tblMStaff where tblMStaff.StaffID = TBLTTimeCard.StaffID LIMIT 1)",
			'CustomerName' => "(select Name From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID LIMIT 1)",
			'CustomerCreatedDate' => "(select DATE_FORMAT(ImplementDate, '%Y/%m/%d') From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID LIMIT 1)",
			//                'Area' => "(select AreaID From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID)",
			'long' => "(select Longitude From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID LIMIT 1)",
			'lat' => "(select Latitude From tblMCustomer where tblMCustomer.CustomerID = TBLTTimeCard.CustomerID LIMIT 1)",
			'slat' => "SUBSTRING_INDEX(CheckinLocation, ',', 1)",
			'slong' => "SUBSTRING_INDEX(CheckinLocation, ',', -1)",
			'checkin' => "CONCAT(DATE_FORMAT(Date, '%m.%d'), '<br/>', DATE_FORMAT(TimeIn, '%H:%i:%s'))",
			'checkout' => "CONCAT(DATE_FORMAT(Date, '%m.%d'), '<br/>', DATE_FORMAT(TimeOut, '%H:%i:%s'))",
			'imgcheckin' => "(select CONCAT(tblTFaceImage.`Source`, Name) From tblTFaceImage where TBLTTimeCard.TimeCardID = tblTFaceImage.TimeCardID AND tblTFaceImage.NAME LIKE '%IN%' LIMIT 1)",
			'imgcheckout' => "(select CONCAT(tblTFaceImage.`Source`, Name) From tblTFaceImage where TBLTTimeCard.TimeCardID = tblTFaceImage.TimeCardID AND tblTFaceImage.NAME LIKE '%OUT%' LIMIT 1)",
			'typeName' => '(select
				tblMRepType.Type1
				From tblTReport
				INNER JOIN tblMRepType ON tblTReport.TypeCode = tblMRepType.TypeCode
				where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID  LIMIT 1
				)',
			'TypeImage' => '(select
				tblMRepType.TypeImage
				From tblTReport
				INNER JOIN tblMRepType ON tblTReport.TypeCode = tblMRepType.TypeCode
				where tblTReport.TimeCardID = TBLTTimeCard.TimeCardID  LIMIT 1
				)',
		])
		->where($conditions)
		->order($order);
        $page = ($this->getRequest()->getData('start') / PAGE_LIMIT_SPECIFIC) + 1;
        $this->paginate = ['page' => $page, 'limit' => PAGE_LIMIT_SPECIFIC, 'maxLimit' => PAGE_MAX_LIMIT];
        $schedules = $this->paginate($query);

        $data = [];
        $date = (isset($params['date'])) ? $params['date'] : date('Y-m-d');
        foreach ($schedules as $item) {
            $distance = $this->TBLTDistance->find()->where(['Date' => $date, 'StaffID' => $item->StaffID])->first();
            $item['distance'] = '';
            if ($distance) {
                $item['distance'] = $distance->Distance;
            }

            //area
            $TBLMArea = $this->TBLMArea->find()->where(['AreaID' => $item->AreaID])->first();
            $emptyObject = new \stdClass();
            $emptyObject->Name = '';
            $item['TBLMArea'] = $TBLMArea ? $TBLMArea : $emptyObject;

            //update report icon
            $item['icon_type'] = (int) $item->TypeCode > 0 ? (int) $item->TypeCode : '';
            $item['type_name'] = $item['typeName']; //vi change to type name 20210811 from Thuy

            //check new
            $item['isNewStaff'] = $this->Common->isNew($item['StaffCreatedDate']);
            $item['isNewCustomer'] = $this->Common->isNew($item['CustomerCreatedDate']);

            array_push($data, $item);
        }

        $response = [
            'recordsTotal' => $query->count(),
            'recordsFiltered' => $query->count(),
            'data' => $data,
        ];
        return $this->response->withType("application/json")->withStringBody(json_encode($response));
    }

    public function getVisits()
    {
        //stop render view
        $this->viewBuilder()->setLayout('ajax');

        $customerID = $this->getRequest()->getData('customerId');
        if ($customerID != '') {
            $orders = [
                "TBLTReport.DateTime",
                "TBLMStaff.StaffID",
                "TBLMStaff.Name",
            ];
            $order = [
                $orders[$this->getRequest()->getData('order.0.column')] => $this->getRequest()->getData('order.0.dir')
            ];
            if ($order == ['TBLTReport.DateTime' => 'desc']) {
                $order = ['TBLTReport.ID' => 'desc'];
            }
            if ($order == ['TBLTReport.DateTime' => 'asc']) {
                $order = ['TBLTReport.ID' => 'asc'];
            }

            $conditions = ['TBLTReport.CustomerID' => $customerID];
            if ($this->getRequest()->getData('psStaffID')) {
                $conditions["TBLMStaff.StaffID LIKE"] = "%" . $this->getRequest()->getData('psStaffID') . "%";
            }
            if ($this->getRequest()->getData('psTimeVisit')) {
                $sTime = str_replace('/', '-', $this->getRequest()->getData('psTimeVisit'));
                $conditions["TBLTReport.DateTime LIKE"] = "%" . $sTime . "%";
            }

            $query = $this->TBLTReport->getHistoris($conditions, $order);
            $page = ($this->getRequest()->getData('start') / PAGE_LIMIT_FULL) + 1;
            $this->paginate = ['page' => $page, 'limit' => PAGE_LIMIT_FULL];
            $logs = $this->paginate($query);
            $data = $logs->toArray();

            foreach ($data as $idx => $e) {
                $imgin = $this->TBLTFaceImage->getImage($e->TimeCardID, 'IN');
                $imgout = $this->TBLTFaceImage->getImage($e->TimeCardID, 'OUT');
                $data[$idx]['imgcheckin'] = $imgin ? $imgin->Source . $imgin->Name : '';
                $data[$idx]['imgcheckout'] = $imgout ? $imgout->Source . $imgout->Name : '';
            }

            $response = [
                'recordsTotal' => $query->count(),
                'recordsFiltered' => $query->count(),
                'data' => $data
            ];
            return $this->response->withType("application/json")->withStringBody(json_encode($response));
        }
        //render selectbox
        $staffIds = $this->TBLMStaff->getStaffIDs();
        $this->set('staffIds', $staffIds);
        $rst = $this->TBLTTimeCard->getTimeVistis();
        $timeVisits = [];
        foreach ($rst as $each) {
            $timeVisits[$this->Date->makeFormat($each->Date, 'Y/m/d')] = $this->Date->makeFormat($each->Date, 'Y/m/d');
        }
        $this->set('timeVisits', $timeVisits);
    }

    /**
     * @return \Cake\Http\Response
     */
    public function confirmPassword()
    {
        $params = $this->request->getData();
        $password = $params['password'];

        $rst = 0;

        if (CST_PASSWORD == $password) {
            $rst = 1;
        }

        $response = [
            'success' => $rst,
        ];
        return $this->response->withType("application/json")->withStringBody(json_encode($response));
    }
}
