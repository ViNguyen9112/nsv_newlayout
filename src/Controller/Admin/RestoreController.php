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
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RestoreController extends AppController
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
        if ($this->getRequest()->is("post")) {
            return $this->execute();
        }
    }

    /**
     *
     */
    private function execute() {
        #upload file
        $files = $this->Common->upload(true);
        #extra images files
//        $output = [];
        if ($files) {
            $tmp_path = $this->Common->build_path(TMP . CST_UPLOAD_FILE_PATH, true);
            $response = Zip::extractTo($tmp_path . $files['file'], WWW_ROOT);
//            if ($response) {
//                $basefile = str_replace(".zip", "", $files['org_filename']);
//                #import database
//                $datasources = ConnectionManager::getConfig('default');
//                $database = $datasources['database'];
//                $user = $datasources['username'];
//                $pass = $datasources['password'];
//                $host = $datasources['host'];
//                $exportFile = WWW_ROOT. "ExportData/{$basefile}.sql";
//
//                exec("mysql --user={$user} --password={$pass} -h {$host} -D {$database} < {$exportFile}  2>&1", $output);
//            }
        }
        $response = [
            'status' => $response ? 1 : 0,
        ];
        return $this->responseJson($response);
    }
}
