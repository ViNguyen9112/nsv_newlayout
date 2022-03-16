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

class CustomerController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMCustomer');
        $this->loadModel('TBLMArea');
        $this->loadModel('TBLMAreaStaff');

        $this->viewBuilder()->setLayout('admin');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        //get master
        $listArea = $this->TBLMArea->find()->order('Name', 'ASC')->toArray();
        $this->set(compact('listArea'));
        $listCustomer = $this->TBLMCustomer->find()->order('CustomerID', 'ASC')->toArray();
        $this->set(compact('listCustomer'));

        $conds = [];
        $arr_areas = $this->Common->chkAreas();
        if (!empty($arr_areas)) {
            $conds = ['AreaID IN' => $arr_areas];
        }
        $listArea = $this->TBLMArea->find()->where([$conds])->order('Name','ASC')->toArray();
        $this->set('listArea', $listArea);
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

            $region = @$params['region'];
            $area = @$params['area'];
            $like = "%{$this->getRequest()->getData('search.value')}%";
            $conditions['OR'] = [
                'CustomerID LIKE' => (mb_detect_encoding($this->getRequest()->getData('search.value')) != "UTF-8") ? $like : "",
                'TBLMCustomer.Name LIKE' => $like,
                'TBLMArea.Name LIKE' => $like,
                'Address LIKE' => $like,
                'Longitude LIKE' => (mb_detect_encoding($this->getRequest()->getData('search.value')) != "UTF-8") ? $like : "",
                'Latitude LIKE' => (mb_detect_encoding($this->getRequest()->getData('search.value')) != "UTF-8") ? $like : "",
                // 'TaxCode LIKE' => $like,
            ];
            $conditions["FlagDelete"] = 0;

            if (!empty($area)) {
                $conditions['TBLMArea.AreaID IN'] = $area;
            }
            if (!empty($region)) {
                $conditions['TBLMArea.Region IN'] = $region;
            }

            // case Area Leader
            $arr_areas = $this->Common->chkAreas();
            if (!empty($arr_areas)) {
                $conditions['TBLMCustomer.AreaID IN'] = $arr_areas;
            }

            $orders = [
                "",
                "CustomerID",
                "TBLMCustomer.Name",
                "Region",
                "TBLMArea.Name",
                "Address",
                "Longitude",
                "Latitude",
                "ImplementDate",
            ];
            if($orders[$this->getRequest()->getData('order.0.column')] == "Region"){
                $order = [
                    "TBLMArea.AreaID" => $this->getRequest()->getData('order.0.dir')
                ];
            } else {
                $order = [
                    $orders[$this->getRequest()->getData('order.0.column')] => $this->getRequest()->getData('order.0.dir')
                ];
            }
            $query = $this->TBLMCustomer->getCustomers($conditions, $order);
            $customers = $this->paginate($query);
            $customers = $customers->toArray();

            $data = [];
            foreach($customers as $customer){
                //check new
                $customer['isNew'] = $this->Common->isNew($customer->ImplementDate);

                array_push($data, $customer);
            }
            $response = [
                'recordsTotal' => $query->count(),
                'recordsFiltered' => $query->count(),
                'data' => $data
            ];
            return $this->responseJson($response);
        }
    }

    /**
     *
     */
    public function edit()
    {
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'Customer', 'action' => 'index']);
        }
        // get request obj
        $request = $this->getRequest();

        //get params
        $params = $request->getData() + $request->getQuery();

        $entity = $this->TBLMCustomer->findById($params['ID'])->first();
        if($entity){
            $entity = $entity;
        } else {
            $entity = new TBLMCustomer();
            $maxSTT = $this->TBLMCustomer->find()->order(['STT' => 'DESC'])->first();
            $entity->STT = intval($maxSTT->STT) + 1;
            $entity->Created_at = date('Y-m-d H:i');

        }
//        $entity->ImplementDate = date('Y-m-d',strtotime($params['ImplementDate']));
        $params['ImplementDate'] = date('Y-m-d');

        $entity = $this->TBLMCustomer->patchEntity($entity, $params, ['validate' => false]);

        $success = 1;
        $conn = ConnectionManager::get('default');
        try {
            $conn->begin();
            $save = $this->TBLMCustomer->save($entity);

            if ($save) {
                $conn->commit();
            }
            else {
                throw new \Exception();
            }
        }
        catch (RecordNotFoundException $e) {
            $success = 0;
            $conn->rollback();
        }
        catch (\Exception $e) {
            $success = 0;
            $conn->rollback();
        }

        $response = [
            'status' => $success,
            'data' => $entity,
            'lst_customers' => $this->TBLMCustomer->getCustomers(),
        ];
        return $this->responseJson($response);
    }

    /**
     *
     */
    public function adjust()
    {
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'Customer', 'action' => 'index']);
        }
        // get request obj
        $request = $this->getRequest();

        //get params
        $params = $request->getData() + $request->getQuery();

        $entity = $this->TBLMCustomer->findByCustomerID($params['ID']);
        $entity = $this->TBLMCustomer->patchEntity($entity, $params, ['validate' => false]);

        $success = 1;
        $conn = ConnectionManager::get('default');
        try {
            $conn->begin();
            $save = $this->TBLMCustomer->save($entity);

            if ($save) {
                $conn->commit();
            }
            else {
                throw new \Exception();
            }
        }
        catch (RecordNotFoundException $e) {
            $success = 0;
            $conn->rollback();
        }
        catch (\Exception $e) {
            $success = 0;
            $conn->rollback();
        }

        $response = [
            'status' => $success,
            'data' => $entity,
        ];
        return $this->responseJson($response);
    }

    /**
     * @return \Cake\Http\Response
     */
    public function delete() {
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'Customer', 'action' => 'index']);
        }
        // get request obj
        $request = $this->getRequest();

        //get params
        $id = $request->getData('id_customer');

        $entity = $this->TBLMCustomer->findById($id)->first();

        $entity = $entity ? $entity : new TBLMCustomer();
        $entity->FlagDelete = 1;
        $success = 1;

        try {
            $this->TBLMCustomer->save($entity);
        }
        catch (RecordNotFoundException $e) {
            $success = 0;
            print($e);
        }
        catch (\Exception $e) {
            $success = 0;
            print($e);
        }

        $response = [
            'status' => $success,
            'lst_customers' => $this->TBLMCustomer->getCustomers(),
        ];
        return $this->responseJson($response);
    }

    /**
     *
     */
    public function search()
    {
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'Customer', 'action' => 'index']);
        }
        // get request obj
        $request = $this->getRequest();

        $id = $request->getData('id_customer');
        $conditions['FlagDelete ='] = 0;
        $customer = $this->TBLMCustomer->getCustomer($id);

        $response = [
            'success' => 1,
            'data' => $customer
        ];
        return $this->responseJson($response);
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
