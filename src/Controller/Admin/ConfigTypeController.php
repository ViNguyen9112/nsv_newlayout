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

use App\Model\Entity\TBLMRegion;
use App\Model\Entity\TBLMRepType;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Response;

class ConfigTypeController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMRepType');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
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
            if(@$params['isDeleted']){
                $conditions = [
                    'FlagDelete IN' => [0,1]                   
                ];
            }else{
                $conditions = [
                    'FlagDelete' => 0
                ];
            }
            $orders = [
                "",
                "TypeCode",
                "Type1",
                "Type2",
                "Type3",
            ];

            $order = [
                $orders[$this->getRequest()->getData('order.0.column')] => $this->getRequest()->getData('order.0.dir')
            ];

            $query = $this->TBLMRepType->getReportsAdmin($conditions, $order);
            $customers = $this->paginate($query);
            $data = $customers->toArray();

            $type_opt = $this->TBLMRepType->getTypeCodes([], true);

            $response = [
                'recordsTotal' => $query->count(),
                'recordsFiltered' => $query->count(),
                'data' => $data,
                'type_opt' => $type_opt,
                'conditions' => $conditions
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

            $entity = new TBLMRepType();
            if ($data['ID']) {
                $entity = $this->TBLMRepType->get($data['ID']);
            }

            $entity = $this->TBLMRepType->patchEntity($entity, $data, ['validate' => false]);

            if (isset($data['temp']) && $data['temp'] && $data['del_image'] != 1) {

                // prepare folder to copy
                $tmp_path = $this->Common->build_path(TMP . CST_UPLOAD_FILE_PATH, true);
                $upload_name = $data['filename'];
                $org_filename = $data['org_filename'];
                $y = substr($upload_name, 2, 2);
                $md = substr($upload_name, 4, 4);
                $path = $tmp_path . DS .$y . DS .$md . DS .$upload_name;
                // target folder image upload
                $dir = WWW_ROOT.'img'.DS.'admin'.DS.'config-type';

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                // copy image
                $src = $dir .DS. $org_filename;
                copy($path, $src);
                $entity->TypeImage = isset($data['org_filename']) ? $data['org_filename'] : '';
            }

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMRepType->save($entity);

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

            $entity = $this->TBLMRepType->get($params['ID']);
            $entity->FlagDelete = 1;

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMRepType->save($entity);

                $this->TBLMRepType->deleteRelated($entity->TypeCode);

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
        $obj = $this->TBLMRepType->get($id);
        $response = [
            'success' => 1,
            'obj' => $obj,
        ];
        return $this->responseJson($response);
    }

    public function upload()
    {
        // get request obj
        $request = $this->getRequest();

        //get params
        $params = $request->getData() + $request->getQuery();

        if (false == $request->is('ajax')) {
            return false;
        }

        //upload image
        $images = $this->Common->upload(true);

        $this->viewBuilder()->disableAutoLayout();

        // render to view
        $this->set(compact('images', 'params'));
    }

}
