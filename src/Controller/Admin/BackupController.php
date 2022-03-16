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

use App\Helper\Zip;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;

class BackupController extends AppController
{
    public function initialize()
    {
        parent::initialize();
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
    public function index()
    {
        $Bytes = disk_free_space("./");
        $dataSize = $this->Common->formatBytes($Bytes);
        $this->set('dataSize', $dataSize);
    }

    // assign to function to delete
    public function checkNumber() {
        $total1 = $total2 = 0;
        for($target = 1; $target <=2; $target++) {
            $fun = "delete{$target}";
            $total = "total{$target}";
            $$total = $this->{$fun}();
        }

        $response = [
            'total_face' => number_format($total1),
            'total_report' => number_format($total2),
            'total_image' => $total1 + $total2
        ];
        return $this->responseJson($response);
    }

    // assign to function to delete
    public function zipFile() {
        $params = $this->request->getData();
        $date1 = new \DateTime($params['datepicker_backup_from']);
        $from = $date1->format("Ymd");
        $date2 = new \DateTime($params['datepicker_backup_to']);
        $to = $date2->format("Ymd");

        #export database
        $datasources = ConnectionManager::getConfig('default');
        $database = $datasources['database'];
        $user = $datasources['username'];
        $pass = $datasources['password'];
        $host = $datasources['host'];
        //$exportFile = WWW_ROOT. "ExportData/{$from}-{$to}.sql";
        $CST_EXPORT_FILE = CST_EXPORT_FILE.date("Ymd");
        $exportFile = WWW_ROOT. "ExportData/{$CST_EXPORT_FILE}.sql";

        exec("mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$exportFile} 2>&1", $output);

        $total1 = $total2 = [];
        for($target = 1; $target <=2; $target++) {
            $fun = "delete{$target}";
            $total = "total{$target}";
            $$total = $this->{$fun}(false, true);
        }

        $files = array_merge($total1, $total2);
        #add export file to zip file
        $files[] = $exportFile;
        /* zip files pdf */
        $path = WWW_ROOT . 'ZipImage';
        if (!file_exists($path)) {
            mkdir($path);
        }
        $fileZip = $from . '-' . $to  . '.zip';
        $destination = $path . '/' . $fileZip;

        $check = Zip::createZip($files, $destination, true);

        $response = [
            'zip_status' => $check ? 1 : 0,
            'zip_file' => $fileZip,
        ];
        return $this->responseJson($response);
    }

    // assign to function to delete
    public function delete() {
        $total1 = $total2 = 0;
        for($target = 1; $target <=2; $target++) {
            $fun = "delete{$target}";
            $total = "total{$target}";
            $$total = $this->{$fun}(true);
        }

        $response = [
            'free_space' => $this->Common->formatBytes($total1+$total2)
        ];
        return $this->responseJson($response);
    }

    //delete face image
    private function delete1($unlink = false, $zip = false, $in = false) {
        $params = $in ? $in : $this->request->getData();

        //init value
        $total = 0;
        $files = [];

        //add number of column
        $date1 = new \DateTime($params['datepicker_backup_from']);
        $date2 = new \DateTime(date("Y/m/d"));
        $from_diffs = $date2->diff($date1)->format("%a");

        $date1 = new \DateTime($params['datepicker_backup_to']);
        $date2 = new \DateTime(date("Y/m/d"));
        $to_diffs = $date2->diff($date1)->format("%a");

        //target face image
        $image_path = WWW_ROOT . "FaceImage";
        $dirs = scandir($image_path);
        foreach ($dirs as $dir) {
            if (in_array($dir, array(".", "..")) === false) {
                $path = $image_path . "/{$dir}";
                $response = $this->Common->backupFiles($path, $from_diffs, $to_diffs, $unlink);
                if ($zip) {
                    $files = array_merge($files, $response['files']);
                }
                elseif ($unlink) {
                    $total += $response['size'];
                }
                else {
                    $total += $response['total'];
                }
            }
        }
        return $zip ? $files : $total;
    }

    //delete report image
    private function delete2($unlink = false, $zip = false, $in = false) {
        $params = $in ? $in :$this->request->getData();

        //init value
        $total = 0;
        $files = [];

        //add number of column
        $date1 = new \DateTime($params['datepicker_backup_from']);
        $date2 = new \DateTime(date("Y/m/d"));
        $from_diffs = $date2->diff($date1)->format("%a");

        $date1 = new \DateTime($params['datepicker_backup_to']);
        $date2 = new \DateTime(date("Y/m/d"));
        $to_diffs = $date2->diff($date1)->format("%a");

        //target face image
        $image_path = WWW_ROOT . "ImageReport";
        $dirs = scandir($image_path);
        foreach ($dirs as $dir) {
            if (in_array($dir, array(".", "..")) === false) {
                $path = $image_path . "/{$dir}";
                $response = $this->Common->backupFiles($path, $from_diffs, $to_diffs, $unlink);
                if ($zip) {
                    $files = array_merge($files, $response['files']);
                }
                elseif ($unlink) {
                    $total += $response['size'];
                }
                else {
                    $total += $response['total'];
                }
            }
        }
        return $zip ? $files : $total;
    }
}
