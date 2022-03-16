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

class AnnualLeaveController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLTNoticeal');

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
            $page = ($this->getRequest()->getData('start') / PAGE_LIMIT_SPECIFIC) + 1;
            $this->paginate = ['page' => $page, 'limit' => PAGE_LIMIT_SPECIFIC, 'maxLimit' => PAGE_MAX_LIMIT];

            //get paramater
            $params = $this->request->getData();

            $fromdate = @$params['fromdate'];
            $todate = @$params['todate'];
			$conditions = array();
			$keyword = $this->getRequest()->getData('search.value');
			if($keyword)
			{
				
				$like = "%{$keyword}%";
				$conditions['OR'] = [
					'TBLTAnnualLeave.StaffID LIKE' => $like,
					'TBLMStaff.Name LIKE' => $like
				];
			}
			if($fromdate)
			{
				$conditions['DATE(TBLTAnnualLeave.FromDate) >='] = date('Y-m-d', strtotime($fromdate));
			}
			if($todate)
			{
				$conditions['DATE(TBLTAnnualLeave.FromDate) <='] = date('Y-m-d', strtotime($todate));
			}
			$conditions['TBLTNoticeal.StaffID'] = $this->Auth->user('StaffID');
            $orders = [
                "",
                "TBLTNoticeal.StaffID",
				"TBLMStaff.Name",
				"TBLMStaff.Position",
				"TBLTAnnualLeave.FromDate",
				"TBLTAnnualLeave.ToDate",
				"TBLTAnnualLeave.Total",
				"TBLTAnnualLeave.Reason",
				"TBLTAnnualLeave.Status"
            ];
            if(isset($orders[$this->getRequest()->getData('order.0.column')])){
                $order = [
                    "TBLTNoticeal.ID" => $this->getRequest()->getData('order.0.dir')
                ];
            } else {
                $order = [
                    'TBLTAnnualLeave.CreatedAt' => 'desc'
                ];
            }
            $query = $this->TBLTNoticeal->getAnnualLeaveRequest($conditions, $order);
            $customers = $this->paginate($query);
            $customers = $customers->toArray();
			foreach($customers as $customer)
			{
				$customer->TBLTAnnualLeave->DetailInfo = $this->TBLMStaff->find()->where(['StaffID' => $customer->TBLTAnnualLeave->StaffID])->first();
				$customer->TBLTAnnualLeave->DetailLeader = $this->TBLTNoticeal->find()->contain(['TBLMStaff'])->where(['TBLTNoticeal.AnnualLeaveID' => $customer->AnnualLeaveID, 'TBLTNoticeal.StaffID !=' =>	$customer->StaffID])->first();
			}
            $response = [
                'recordsTotal' => $query->count(),
                'recordsFiltered' => $query->count(),
                'data' => $customers
            ];
            return $this->responseJson($response);
        }
    }

    /**
     *
     */
	public function edit(){
		$isPost = $this->request->is('post');
		$staff = $this->TBLMStaff->find()->where(['StaffID' => $this->Auth->user('StaffID')])->first();
		$leaderConfig = array('Leader', 'Area Leader');
		if (in_array($staff->Position, $leaderConfig)) 
		{
			die;
		}
		if($isPost)
		{
			$this->loadModel('TBLTAnnualLeave');
			$this->loadModel('TBLTNoticeal');
			$t = $this->getRequest()->getData('Type');
			if($t == 'action')
			{
				$ID = $this->getRequest()->getData('ID');
				$action = $this->getRequest()->getData('Action');
				if(in_array($action, array('approve', 'refuse')) && count($ID) == 2 && (int)$ID[0] > 0 && (int)$ID[1] > 0)
				{
					$actionVal = $action == 'approve' ? 3 : 2;
					$OverTimeRS = $this->TBLTAnnualLeave->updateAll(
						["Status" => $actionVal, "Notification" => 0, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[0]] 
					);
					$annualLeaveID = $this->TBLTNoticeal->find()->where(['ID' => $ID[1]])->first()->AnnualLeaveID;
					$noticeRS = $this->TBLTNoticeal->updateAll(
						["Approved" => $actionVal, 'UpdatedAt' => date('Y-m-d H:i:s'), 'Notification' => 0],
						["AnnualLeaveID" => $annualLeaveID] 
					);
					if($noticeRS && $OverTimeRS)
					{
						$result['data'] = $actionVal;
					}
				}
			}
			return $this->response->withType('application/json')->withStringBody(json_encode($result));
		}
	}
}
