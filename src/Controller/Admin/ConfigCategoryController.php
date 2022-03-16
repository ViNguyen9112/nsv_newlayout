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

use App\Model\Entity\TBLMRepCategory;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Response;


class ConfigCategoryController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMRepCategory');
        $this->loadModel('TBLMRepType');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->loadComponent('Common');

        //set master
        $type_opt = $this->TBLMRepType->getTypeCodes();
        $this->set('type_opt', $type_opt);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->setLayout('admin');
    }

    /**
     * @return \Cake\Http\Response
     */
    public function index()
    {
        if ($this->getRequest()->is('ajax')) {
            $page = ($this->getRequest()->getData('start') / PAGE_LIMIT_SPECIFIC) + 1;
            $this->paginate = ['page' => $page, 'limit' => PAGE_LIMIT_SPECIFIC, 'maxLimit' => PAGE_MAX_LIMIT];

            //get paramater
            $params = $this->request->getData();
            $TypeCode = @$params['TypeCode'];
            $conditions = [];
            if(@$params['TypeCode']){
                $conditions[] = [
                    'TypeCode' => @$params['TypeCode']
                ];
            }
            if(@$params['CatCode']){
                $conditions = [
                    'CatCode' => @$params['CatCode']
                ];
            }

            if(@$params['isDeleted']){
                $conditions[] = [
                    'FlagDelete IN' => [0,1]                   
                ];
            }else{
                $conditions[] = [
                    'FlagDelete' => 0
                ];
            }
            $orders = [
                "",
                "CatCode",
                "CatName1",
                "CatName2",
                "CatName3",
                "TypeCode",
                "TypeCat",
                "CatSortNumber"
            ];

            $order = [
                $orders[$this->getRequest()->getData('order.0.column')] => $this->getRequest()->getData('order.0.dir')
            ];
            $query = $this->TBLMRepCategory->getCategoriesAdmin($conditions, $order);
            $customers = $this->paginate($query);
            $data = $customers->toArray();

            $category_opt = $this->TBLMRepCategory->getCatCodes([], true);

            $response = [
                'recordsTotal' => $query->count(),
                'recordsFiltered' => $query->count(),
                'data' => $data,
                'category_opt' => $category_opt
            ];
            return $this->responseJson($response);
        }
    }

    /**
     * @return \Cake\Http\Response
     */
    public function edit() {
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'ConfigType', 'action' => 'index']);
        }
        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();

            $entity = new TBLMRepCategory();
            if ($data['ID']) {
                $entity = $this->TBLMRepCategory->get($data['ID']);
            }else{
                $data['CatSortNumber'] = $this->Common->getMaxSort("TBLMRepCategory", "CatSortNumber", 'TypeCode', $data['TypeCode'])+1;
            }
            $entity = $this->TBLMRepCategory->patchEntity($entity, $data, ['validate' => false]);

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMRepCategory->save($entity);

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
    }

    /**
     * @return Response
     */
    public function delete() {
        if ($this->getRequest()->is('post')) {
            $params = $this->getRequest()->getData();

            $entity = $this->TBLMRepCategory->get($params['ID']);
            $entity->FlagDelete = 1;
            
            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $resetSort = $this->resetSortNumber($entity->TypeCode, $entity->CatCode, $entity->CatSortNumber);
                $entity->CatSortNumber = null;
                $save = $this->TBLMRepCategory->save($entity);

                $this->TBLMRepCategory->deleteRelated($entity->CatCode);
                
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
    }
    public function resetSortNumber($TypeCode, $CatCode, $CatSortNumber){
        $list_cat_by_type = $this->TBLMRepCategory->find()
            ->where([
                'TypeCode' =>  $TypeCode
            ]);
        $save = true;
        foreach ($list_cat_by_type as $key => $value) {
            if($value->CatSortNumber > $CatSortNumber){
                $value->CatSortNumber = $value->CatSortNumber-1;
               $save = $save && $this->TBLMRepCategory->save($value);
            }
        }
        return $save;
    }
    /**
     * @return \Cake\Http\Response|null
     */
    public function search()
    {
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'Staff', 'action' => 'index']);
        }
        // get request obj
        $request = $this->getRequest();

        $id = $request->getData('ID');
        $conditions["FlagDelete ="] = 0;
        $obj = $this->TBLMRepCategory->get($id);
        $response = [
            'success' => 1,
            'obj' => $obj,
        ];
        return $this->responseJson($response);
    }

    public function updatesort(){
        if(false == $this->request->is('ajax')) {
            return $this->redirect(['controller' => 'Staff', 'action' => 'index']);
        }
         if ($this->getRequest()->is('post')) {
            $params = $this->getRequest()->getData();

            $entity = $this->TBLMRepCategory->get($params['ID']);
            $type = $params['TYPE'];
            //update sort LISST BY TYPE

            $list_cat_by_type = $this->TBLMRepCategory->find()
            ->where([
                        'TypeCode' =>  $entity->TypeCode,
                        'ID != '   => $params['ID']
                    ]);


            $success = 1;
            $conn = ConnectionManager::get('default');
            $save_other = false;
            try {
                $conn->begin();


                if($type == 'up' ){
                    $max = $this->Common->getMaxSort("TBLMRepCategory", "CatSortNumber","TypeCode", $entity->TypeCode);
                    if($entity->CatSortNumber == $max){
                         $response = [
                        'status' => 3,
                        'data' => $entity,
                    ];
                    return $this->responseJson($response);
                    }
                }

                if($type == 'down' ){

                    if($entity->CatSortNumber <= 1){
                         $response = [
                        'status' => 3,
                        'data' => $entity,
                    ];
                    return $this->responseJson($response);
                    }
                }

                foreach ($list_cat_by_type as $value) {
                    if($type == 'down' && ($value->CatSortNumber+1) == ($entity->CatSortNumber)){
                        $value->CatSortNumber = $entity->CatSortNumber;
                        $save_other = $this->TBLMRepCategory->save($value);
                    }
                    if($type == 'up' && ($value->CatSortNumber-1) == ($entity->CatSortNumber)){
                        $value->CatSortNumber = $entity->CatSortNumber;
                        $save_other = $this->TBLMRepCategory->save($value);
                    }
                }
                //UPDATE ROW
                if($type == 'down'){
                    if($entity->CatSortNumber > 1){
                        $entity->CatSortNumber = $entity->CatSortNumber-1;
                    }
                }
                if($type == 'up'){
                    $max = $this->Common->getMaxSort("TBLMRepCategory", "CatSortNumber","TypeCode", $entity->TypeCode);
                    if($entity->CatSortNumber <= $max){
                        $entity->CatSortNumber = $entity->CatSortNumber+1;
                    }
                }
                $save = $this->TBLMRepCategory->save($entity);


                if ($save && $save_other) {
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
    }
}
