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

class OvertimeController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLTNotice');
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
					'TBLTOverTime.StaffID LIKE' => $like,
					'TBLMStaff.Name LIKE' => $like
				];
			}
			if($fromdate)
			{
				$conditions['DATE(TBLTOverTime.StartTime) >='] = date('Y-m-d', strtotime($fromdate));
			}
			if($todate)
			{
				$conditions['DATE(TBLTOverTime.StartTime) <='] = date('Y-m-d', strtotime($todate));
			}
			$conditions['TBLTNotice.Approved >'] = 0;

			$oconditions['TBLTNotice.Approved'] = 0;
			$oconditions['TBLTNotice.StaffID'] = $this->Auth->user('StaffID');
            $orders = [
                "",
                "TBLTNotice.StaffID",
				"TBLMStaff.Name",
				"TBLMStaff.Position",
				"TBLTOverTime.StartTime",
				"TBLTOverTime.StartTime",
				"TBLTOverTime.EndTime",
				"TBLTOverTime.Total",
				"TBLTOverTime.Status"
            ];
            if(isset($orders[$this->getRequest()->getData('order.0.column')])){
                $order = [
                    "TBLTNotice.ID" => $this->getRequest()->getData('order.0.dir')
                ];
            } else {
                $order = [
                    'TBLTNotice.CreatedAt' => 'desc'
                ];
            }
            $query = $this->TBLTNotice->getOvertimeRequest($conditions, $order, $oconditions);
            $customers = $this->paginate($query);
            $customers = $customers->toArray();
			foreach($customers as $customer)
			{
				$customer->TBLTOverTime->DetailInfo = $this->TBLMStaff->find()->where(['StaffID' => $customer->TBLTOverTime->StaffID])->first();
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
			$this->loadModel('TBLTOverTime');
			$this->loadModel('TBLTNotice');
			$t = $this->getRequest()->getData('Type');
			if($t == 'action')
			{
				$ID = $this->getRequest()->getData('ID');
				$action = $this->getRequest()->getData('Action');
				if(in_array($action, array('approve', 'refuse')) && count($ID) == 2 && (int)$ID[0] > 0 && (int)$ID[1] > 0)
				{
					$actionVal = $action == 'approve' ? 1 : 2;
					$noticeRS = $this->TBLTNotice->updateAll(
						["Approved" => $actionVal, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[1]] 
					);
					$OverTimeRS = $this->TBLTOverTime->updateAll(
						["Status" => $actionVal, "Notification" => 0, 'UpdatedAt' => date('Y-m-d H:i:s')],
						["ID" => $ID[0]] 
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
