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

use App\Model\Entity\TBLMArea;
use App\Model\Entity\TBLMRegion;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Response;

/**
 * @property bool|object TBLMRegion
 */
class ConfigurationController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLMRegion');
        $this->loadModel('TBLMArea');
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
     *
     */
    public function region()
    {
        if ($this->getRequest()->is('post')) {
            $params = $this->getRequest()->getData();

            $data = array(
                'RegionID' => $params['RegionCode'],
                'Region' => $params['RegionName'],
            );
            $entity = new TBLMRegion();
            if ($params['ID']) {
                $entity = $this->TBLMRegion->get($params['ID']);
            }
            $entity = $this->TBLMRegion->patchEntity($entity, $data, ['validate' => false]);

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMRegion->save($entity);

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
        $regions = $this->TBLMRegion
            ->find()
            ->contain('TBLMArea')
//            ->where(['Deleted' => 0])
            ->order(['Region']);
        $this->set('regions', $regions);
    }

    /**
     * @return Response
     */
    public function deleteRegion() {
        if ($this->getRequest()->is('post')) {
            $params = $this->getRequest()->getData();

            $entity = $this->TBLMRegion->get($params['ID']);
            $entity->Deleted = 1;

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMRegion->save($entity);

                $this->TBLMRegion->deleteRelated($entity->RegionID);

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
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function area()
    {
        if ($this->getRequest()->is('post')) {
            $params = $this->getRequest()->getData();

            $data = array(
                'AreaID' => $params['AreaCode'],
                'Name' => $params['AreaName'],
                'Region' => $params['Region'],
            );
            $entity = new TBLMArea();
            if ($params['ID']) {
                $entity = $this->TBLMArea->get($params['ID']);
            }
            $entity = $this->TBLMArea->patchEntity($entity, $data, ['validate' => false]);

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMArea->save($entity);

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
        $areas = $this->TBLMArea
            ->find()
            ->contain('TBLMCustomer')
//            ->where(['Deleted' => 0])
            ->order(['Name']);
        $this->set('areas', $areas);
    }


    /**
     * @return Response
     */
    public function deleteArea() {
        if ($this->getRequest()->is('post')) {
            $params = $this->getRequest()->getData();

            $entity = $this->TBLMArea->findByAreaID($params['AreaID']);
            $entity->Deleted = 1;

            $success = 1;
            $conn = ConnectionManager::get('default');
            try {
                $conn->begin();
                $save = $this->TBLMArea->save($entity, ['validate' => false]);

                $this->TBLMArea->deleteRelated($entity->AreaID);

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
}
