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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Twig\TokenParser\WithTokenParser;
use ZipArchive;

class FaceImageController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('TBLTFolder');
        $this->loadModel('TBLMStaff');
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

    /**
     *
     */
    public function index()
    {
        $folder = $this->TBLTFolder
            ->find()
            ->contain(['TBLMStaff'])
            ->where([
                "Type" => "Staff",
                "ParentFolder IS NULL",
                'TBLMStaff.FlagDelete' => 0
            ])
            ->order(['TBLTFolder.Name' => 'ASC']);
        $this->set('folder', $folder);
        $subFolder = $this->TBLTFolder->find()->where(["Type" => "Sub"])->order('LENGTH(Name),Name');
        $this->set('subFolder', $subFolder);
    }

    public function sort(){
        $sort = $this->getRequest()->getData('sort');
        $result['folders'] = $this->TBLTFolder
            ->find()
            ->contain(['TBLMStaff'])
            ->where([
                "Type" => "Staff",
                "TBLMStaff.FlagDelete" => 0
            ])
            ->order(['TBLTFolder.Name' => $sort]);
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function newFolder(){
        $newFolder = [];
        $name = $this->TBLTFolder->find()
            ->where([
                "Name LIKE 'New Folder%'"
            ])
            ->select('Name')
            ->order('LENGTH(Name),Name')
            ->last();

        if($name){
            if(preg_match('!\d+!', $name, $match)){
                $_name = intval($match[0]) + 1;
            } else {
                $_name = 1;
            }

            $new = $this->TBLTFolder->newEntity();
            $new->Name = 'New Folder('. strval($_name) .')';
            $new->Type = "Sub";
            $new->Created_at = date('Y-m-d H:i:s');
            $newFolder = $this->TBLTFolder->save($new);
        } else {
            $new = $this->TBLTFolder->newEntity();
            $new->Name = 'New Folder';
            $new->Type = "Sub";
            $new->Created_at = date('Y-m-d H:i:s');
            $newFolder = $this->TBLTFolder->save($new);
        }

        $result['newFolder'] = $newFolder;
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function getSubFolder(){
        $result['listFolder'] = $this->TBLTFolder->find()->where(["Type" => "Sub"])->order('LENGTH(Name),Name');
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public static function techniqueFolder($statusTool, $parentFolder, $arrFolder){
        $result = [];
        $result['listFolder'] = [];
        $childrenFolder = [];
        $quickAccess = [];
        $arrFolderSuccess = [];
        $arrFolderError = [];

        $parent = TableRegistry::getTableLocator()->get('TBLTFolder')->find()->where(['ID' => $parentFolder])->first();
        if($parent->ChildrenFolder){
            $childrenFolder = unserialize($parent->ChildrenFolder);
        }
        if($parent->QuickAccess){
            $quickAccess = unserialize($parent->QuickAccess);
        }

        foreach($arrFolder as $item){
            $item = $item['OverlayID'];
            $folder = TableRegistry::getTableLocator()->get('TBLTFolder')->find()->where(['ID' => $item])->first();

            if($folder){
                if($statusTool == 'copied'){
                    if(!in_array($item, $childrenFolder)){
                        // copy data (Folder)
                        $copyFolderTable = TableRegistry::getTableLocator()->get('TBLTFolder');
                        $copyFolder = $copyFolderTable->newEntity();
                        $copyFolder->Name = $folder->Name;
                        $copyFolder->Type = "Copy";
                        $copyFolder->ParentFolder = $parentFolder;
                        $copyFolder->Created_at = date('Y-m-d H:i:s');
                        $savedCopy = $copyFolderTable->save($copyFolder);

                        // array folder copy success
                        array_push($arrFolderSuccess, $savedCopy->ID);

                        // copy data (FaceImage)
                        $images = TableRegistry::getTableLocator()->get('TBLTFaceImage')->find()->where(['FolderID' => $item]);
                        foreach($images as $image){
                            $copyImgTable = TableRegistry::getTableLocator()->get('TBLTFaceImage');
                            $copyImg = $copyImgTable->newEntity();
                            $copyImg->Name = $image->Name;
                            $copyImg->Source = $image->Source;
                            $copyImg->FolderID = $savedCopy->ID;
                            // $copyImg->TimeCardID = $image->TimeCardID;
                            $copyImg->Created_at = date('Y-m-d H:i:s');
                            $copyImgTable->save($copyImg);
                        }
                    }
                } else if($statusTool == "cutted") {
                    // set ParentFolder
                    $child = TableRegistry::getTableLocator()->get('TBLTFolder')->find()->where(['ID' => $item])->first();
                    $child->ParentFolder = $parent->ID;
                    TableRegistry::getTableLocator()->get('TBLTFolder')->save($child);
                    array_push($arrFolderSuccess, $item);
                } else {
                    if(!in_array($item, $quickAccess)){
                        // linked: append ChildrenFolder
                        array_push($arrFolderSuccess, $item);
                    }
                }
            } else {
                array_push($arrFolderError, $item);
            }
        }

        if(!empty($arrFolderSuccess)){
            foreach($arrFolderSuccess as $item){
                $folder = TableRegistry::getTableLocator()->get('TBLTFolder')->find()->where(['ID' => $item])->first()->toArray();
                $folder['Reason'] = $statusTool;
                array_push($result['listFolder'], $folder);
                array_push($childrenFolder, $item);
                array_push($quickAccess, $item);
            }
        }

        if($statusTool == 'copied'){
            //append ChildrenFolder
            $parent->ChildrenFolder = serialize($childrenFolder);
            TableRegistry::getTableLocator()->get('TBLTFolder')->save($parent);
        } else if($statusTool == 'cutted'){
            // update mainFolder
            $result['mainFolder'] = TableRegistry::getTableLocator()->get('TBLTFolder')->find()->where(["Type" => "Staff", "ParentFolder IS NULL"])->order(['Name' => 'ASC']);
        } else {
            $parent->QuickAccess = serialize($quickAccess);
            TableRegistry::getTableLocator()->get('TBLTFolder')->save($parent);
        }

        if(!empty($arrFolderError)){
            $result['arrFolderError'] = $arrFolderError;
        }

        return $result;
    }

    public function pasteFolder(){
        $parentFolder = $this->getRequest()->getData('parentFolder');
        $arrFolder = $this->getRequest()->getData('validFolder');
        $statusTool = $this->getRequest()->getData('statusTool');

        $result = FaceImageController::techniqueFolder($statusTool,$parentFolder,$arrFolder);

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function replaceFolder(){
        $result['success'] = 0;
        $statusTool = $this->getRequest()->getData('statusTool');
        $parentFolder = $this->getRequest()->getData('parentFolder');
        $arrDuplicateFolder = $this->getRequest()->getData('duplicateFolder');

        foreach($arrDuplicateFolder as $item){
            $this->TBLTFaceImage->deleteAll(['FolderID' => $item['ExistID']]);
            $folder = $this->TBLTFolder->find()->where(['ID' => $item['ExistID']])->first();
            $this->TBLTFolder->delete($folder);
        }
        $result = FaceImageController::techniqueFolder($statusTool,$parentFolder,$arrDuplicateFolder);
        $result['success'] = 1;
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function mergeFolder(){
        $result['success'] = 0;
        $statusTool = $this->getRequest()->getData('statusTool');
        $parentFolder = $this->getRequest()->getData('parentFolder');
        $arrDuplicateFolder = $this->getRequest()->getData('duplicateFolder');

        foreach($arrDuplicateFolder as $item){
            $imageInOverlay = $this->TBLTFaceImage->find()->where(['FolderID' => $item['OverlayID']]);
            $imageInExist =  $this->TBLTFaceImage->find()->where(['FolderID' => $item['ExistID']]);

            foreach($imageInOverlay as $img){
                foreach($imageInExist as $imgExist){
                    if($img->Name != $imgExist->Name || $img->Source != $imgExist->Source){
                        $copyImgTable = TableRegistry::getTableLocator()->get('TBLTFaceImage');
                        $copyImg = $copyImgTable->newEntity();
                        $copyImg->Name = $img->Name;
                        $copyImg->Source = $img->Source;
                        $copyImg->FolderID = $item['ExistID'];
                        // $copyImg->TimeCardID = $img->TimeCardID;
                        $copyImg->Created_at = date('Y-m-d H:i:s');
                        $copyImgTable->save($copyImg);
                    }
                }
            }

            if($statusTool == 'cutted'){
                // set ParentFolder
                $child = TableRegistry::getTableLocator()->get('TBLTFolder')->find()->where(['ID' => $item['OverlayID']])->first();
                $child->ParentFolder = $parentFolder;
                TableRegistry::getTableLocator()->get('TBLTFolder')->save($child);
            }
        }

        $result['success'] = 1;

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function renameFolder(){
        $result = [];
        $id = $this->getRequest()->getData('ID');
        $name= $this->getRequest()->getData('Name');

        $folder = $this->TBLTFolder->find()->where(['ID' => $id])->first();
        $folder->Name = $name;
        if($this->TBLTFolder->save($folder)){
            $result['newName'] = $name;
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function deleteFolder(){
        $mains = $this->getRequest()->getData('MainFolder');
        $subs = $this->getRequest()->getData('SubFolder');

        if($mains){
            foreach($mains as $item){
                // delete FaceImage
                $this->TBLTFaceImage->deleteAll(['FolderID' => $item]);
                // delete Folder
                $folder = $this->TBLTFolder->find()->where(['ID' => $item])->first();
                $this->TBLTFolder->delete($folder);
            }
        }

        if($subs){
            foreach($subs as $item){
                $folder = $this->TBLTFolder->find()->where(['ID' => $item])->first();
                if($folder->childrenFolder){
                    $children = unserialize($folder->ChildrenFolder);
                    foreach($children as $child){
                        $this->TBLTFaceImage->deleteAll(['FolderID' => $child]);
                        $childFolder = $this->TBLTFolder->find()->where(['ID' => $child])->first();
                        if($childFolder){
                            $this->TBLTFolder->delete($childFolder);
                        }
                    }
                }
                // delete Folder
                $folder = $this->TBLTFolder->find()->where(['ID' => $item])->first();
                $this->TBLTFolder->delete($folder);
            }
        }
        $result['success'] = 1;
        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public static function getRandomString($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    public function downloadFolder(){
        set_time_limit(0);
        $mains = $this->getRequest()->getData('MainFolder');
        $subs = $this->getRequest()->getData('SubFolder');

        // TODO: array sub folder ->download contains folders inside
        // (Type=>Copy (as zip folders Copy,Cut))
        $DIR = WWW_ROOT."zip/".date('Y-m-d')."/";
        if (!file_exists($DIR)) {
            mkdir($DIR);
        }
        $zip = new \ZipArchive();
        $file_path = "";
        $file_name = "";

        if(count($mains) == 1 && count($subs) == 0){
            $file_path = $DIR;
            foreach($mains as $item){
                $folder = $this->TBLTFolder->find()
                    ->where(['ID'=>$item])
                    ->first();
                $file_name = str_replace(" ","",$folder->Name)."_".date('Y-m-d').".zip";
            }
        } else {
            $randString = FaceImageController::getRandomString(20);
            $file_path = $DIR.$randString."/";
            $file_name = "FaceImage"."_".date('Y-m-d').".zip";
        }

        if (!file_exists($file_path)) {
            mkdir($file_path);
        }

        $archive_file_name = $file_path.$file_name;
        //create the file and throw the error if unsuccessful
        // $archive_file_name = $file_path."example.zip";
        if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
            $zip->open($archive_file_name, ZIPARCHIVE::OVERWRITE );
        }

        if(count($mains) > 0){
            foreach($mains as $item){
                $folder = $this->TBLTFolder->find()
                    ->where(['ID'=>$item])
                    ->first();
                $folderName = $folder->Name;
                $images = $this->TBLTFaceImage->find()
                    ->where(['FolderID'=>$item]);

                //add each files of $file_name array to archive
                foreach($images as $image)
                {
                    $imgSource = WWW_ROOT.$image->Source;
                    $imgName = $image->Name;
                    if(file_exists($imgSource.$imgName)){
                        $zip->addFile($imgSource.$imgName, $folderName."/".$imgName);
                    }
                }
            }
        }

        if(count($subs) > 0){
            foreach($subs as $item){
                $subFolder = $this->TBLTFolder->find()->where(['ID' => $item])->first();
                $subFolderName = $subFolder->Name;
                $children = unserialize($subFolder->ChildrenFolder);
                foreach($children as $child){
                    $folder = $this->TBLTFolder->find()
                        ->where(['ID'=>$child])
                        ->first();
                    $folderName = $folder->Name;
                    $images = $this->TBLTFaceImage->find()
                        ->where(['FolderID'=>$child]);

                    //add each files of $file_name array to archive
                    foreach($images as $image)
                    {
                        $imgSource = WWW_ROOT.$image->Source;
                        $imgName = $image->Name;
                        if(file_exists($imgSource.$imgName)){
                            $zip->addFile($imgSource.$imgName, $subFolderName."/".$folderName."/".$imgName);
                        }
                    }
                }

            }
        }
        $zip->close();

		if (file_exists($archive_file_name)) {
			return $this->response
			->withType('application/json')
			->withStringBody(json_encode(array(
				'success' => 1,
				'File' => str_replace(WWW_ROOT, "", $file_path) . $file_name
			)));
		}else{
			return $this->response
			->withType('application/json')
			->withStringBody(json_encode(array(
				'error' => 1
			)));
        }

    }

    public function getChildrenFolder(){
        $result = [];
        $result['children'] = [];
        $id = $this->getRequest()->getData('id');


        $folder = $this->TBLTFolder->find()->where(['ID' => $id])->first();
        if($folder){
            // is children
            if($folder->ChildrenFolder){
                $folders = unserialize($folder->ChildrenFolder);
                foreach($folders as $item){
                    $child = $this->TBLTFolder->find()->where(['ID' => $item])->first();
                    if($child){
                        $child['Reason'] = 'copied';
                        array_push($result['children'], $child->toArray());
                    }
                }
            }

            // quick access
            if($folder->QuickAccess){
                $folders = unserialize($folder->QuickAccess);
                foreach($folders as $item){
                    $child = $this->TBLTFolder->find()->where(['ID' => $item])->first()->toArray();
                    $child['Reason'] = 'shortcuted';
                    array_push($result['children'], $child);
                }
            }
        }

        // have parent
        $folders = $this->TBLTFolder->find()->where(['Type' => 'Staff', 'ParentFolder' => $id]);
        if($folders){
            foreach($folders as $item){
                $item->Reason = 'cutted';
                array_push($result['children'], $item);
            }
        }

        // sort listFolder
        $name = array();
        foreach ($result['children'] as $key => $row)
        {
            $name[$key] = $row['Name'];
        }
        array_multisort($name, SORT_ASC, $result['children']);

        return $this->response->withType('application/json')->withStringBody(json_encode($result));
    }

    public function gallery($folderid){
        $folder = $this->TBLTFolder->find()->where(['ID' => $folderid])->first();
        if($folder){
            $this->set('folder', $folder);
        }
        $images = $this->TBLTFaceImage->find()
            ->where(['FolderID' => $folderid])
            ->order(['Created_at' => 'DESC']);

        if(!empty($images->toArray())){
            $sortedData = array();
            foreach ($images as $element) {
                $timecard = $this->TBLTTimeCard->find()->where(['TimeCardID' => $element['TimeCardID']])->first();
                $customer = $this->TBLMCustomer->find()->where(['CustomerID' => $timecard['CustomerID']])->first();
                $area = $this->TBLMArea->find()->where(['AreaID' => $customer['AreaID']])->first();

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
            $this->set('images', $sortedData);
        }

    }
}
