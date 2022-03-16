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
use Cake\ORM\TableRegistry;

class GalleryController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLTFolder');
        $this->loadModel('TBLTFaceImage');
        $this->loadModel('TBLTTimeCard');
        $this->loadModel('TBLMCustomer');
        $this->loadModel('TBLMArea');

        $this->viewBuilder()->setLayout('admin');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

    public static function sortedArrByDate($data){
        $sortedData = array();
        foreach ($data as $element) {
            $timecard = TableRegistry::getTableLocator()->get('TBLTTimeCard')->find()->where(['TimeCardID' => $element['TimeCardID']])->first();
            $customer = TableRegistry::getTableLocator()->get('TBLMCustomer')->find()->where(['CustomerID' => $timecard['CustomerID']])->first();
            $area = TableRegistry::getTableLocator()->get('TBLMArea')->find()->where(['AreaID' => $customer['AreaID']])->first();

            if(strpos($element['Name'],'IN.jpg')){
                $element['Type'] = "Checked-in";
            } else {
                $element['Type'] = "Checked-out";
            }
            $element['Time'] = substr($element['Name'],13,2) . ":" . substr($element['Name'],15,2);
            $element['Area'] = $area['Name'];
            $element['Customer'] = $customer['Name'];

            $timestamp = strtotime($element['Created_at']);
            $date = date("Y/m/d", $timestamp); //truncate hours:minutes:seconds
            if ( ! isSet($sortedData[$date]) ) { //first entry of that day
                $sortedData[$date] = array($element);
            } else { //just push current element onto existing array
                $sortedData[$date][] = $element;
            }
        }
        return $sortedData;
    }
    public function sort(){
        $result = [];
        $sort = $this->getRequest()->getData('sort');
        $folderid = $this->getRequest()->getData('folderid');

        $images = $this->TBLTFaceImage->find()
            ->where(['FolderID' => $folderid])
            ->order(['Created_at' => $sort]);

        $clone = array();
        foreach ($images as $image) {
            $file = $image['Source']."/".$image['Name'];
            if (!file_exists(WWW_ROOT.$file)) {
                $image['Source'] = "img/";
                $image['Name'] = "deleted.jpg";
            }
            $clone[] =  $image;
        }
        $images = $clone;

        if($images){
            $result['data'] = GalleryController::sortedArrByDate($images);
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function filter(){
        $date = $this->getRequest()->getData('date');
        $date = str_replace("/","-", $date);
        $images = $this->TBLTFaceImage->find()->where(['DATE(Created_at)' => $date]);
        $clone = array();
        foreach ($images as $image) {
            $file = $image['Source']."/".$image['Name'];
            if (!file_exists(WWW_ROOT.$file)) {
                $image['Source'] = "img/";
                $image['Name'] = "deleted.jpg";
            }
            $clone[] =  $image;
        }
        $images = $clone;
        if($images){
            $result['data'] = GalleryController::sortedArrByDate($images);
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

}
