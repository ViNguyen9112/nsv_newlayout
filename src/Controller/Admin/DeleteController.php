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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DeleteController extends AppController
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
        if ($this->getRequest()->is('post')) {
            return $this->delete();
        }
        else {
            $total_face = $this->delete1(false, array('datepicker_face_to' => date('Y/m/d', strtotime("-2 month"))));
            $total_report = $this->delete2(false, array('datepicker_report_to' => date('Y/m/d', strtotime("-2 month"))));
            $this->set("total_face", number_format($total_face));
            $this->set("total_report", number_format($total_report));
        }
    }

    // assign to function to delete
    private function delete() {
        $target = $this->getRequest()->getData('target');

        $fun = "delete{$target}";
        $total = $this->{$fun}(true);

        $response = [
            'total' => number_format($total)
        ];
        return $this->responseJson($response);
    }

    // assign to function to delete
    public function checkNumber() {
        $target = $this->getRequest()->getData('target');

        $fun = "delete{$target}";
        $total = $this->{$fun}();

        $response = [
            'total' => number_format($total)
        ];
        return $this->responseJson($response);
    }

    //delete face image
    private function delete1($unlink = false, $in = false) {
        $params = $in ? $in : $this->request->getData();

        //init value
        $total = 0;

        //add number of column
        $date1 = new \DateTime($params['datepicker_face_to']);
        $date2 = new \DateTime(date("Y/m/d"));
        $diffs = $date2->diff($date1)->format("%a");

        //target face image
        $image_path = WWW_ROOT . "FaceImage";
        $dirs = scandir($image_path);
        foreach ($dirs as $dir) {
            if (in_array($dir, array(".", "..")) === false) {
                $path = $image_path . "/{$dir}";
                $total += $this->Common->deleteFiles($path, $diffs, $unlink);
            }
        }
        return $total;
    }

    //delete report image
    private function delete2($unlink = false, $in = false) {
        $params = $in ? $in :$this->request->getData();

        //init value
        $total = 0;

        //add number of column
        $date1 = new \DateTime($params['datepicker_report_to']);
        $date2 = new \DateTime(date("Y/m/d"));
        $diffs = $date2->diff($date1)->format("%a");

        //target face image
        $image_path = WWW_ROOT . "ImageReport";
        $dirs = scandir($image_path);
        foreach ($dirs as $dir) {
            if (in_array($dir, array(".", "..")) === false) {
                $path = $image_path . "/{$dir}";
                $total += $this->Common->deleteFiles($path, $diffs, $unlink);
            }
        }
        return $total;
    }
}
