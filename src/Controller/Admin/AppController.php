<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\App;
use Cake\Utility;
use  Cake\Controller\Component;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     * Use this method to add common initialization code like loading components.
     * e.g. `$this->loadComponent('Security');`
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Date');
        $this->loadComponent('Cookie');
        $this->loadComponent('Common');
        $this->loadComponent('Auth', [
            'logoutRedirect' => "/admin",
            'loginAction' => [
                'controller' => 'User',
                'action' => 'login'
            ],
            'authenticate' => [
                'Cookie' => [
                    'fields' => [
                        'username' => 'StaffID',
                        'password' => 'Password',
                    ],
                    'userModel' => 'TBLMStaff',
                    // scope is deprecated, use 'finder' instead
                    //'scope' => ['QuitJobDate IS NULL'],
                    'finder' => 'admin',
                    'cookie' => [
                        'name' => 'remember_me_cookie',
                        'expires' => '+2 weeks',
                    ],
                ],
                'Ldap' => [
                    'fields' => [
                        'username' => 'StaffID',
                        'password' => 'Password',
                    ],
                    'userModel' => 'TBLMStaff',
                    // scope is deprecated, use 'finder' instead
                    //'scope' => ['QuitJobDate IS NULL'],
                    'finder' => 'admin',
                ],
            ],
            'authError' => 'ID and Password are not correct, please try again !',
            'storage' => [
                'className' => 'Session',
                'key' => 'Auth.Admin',
            ],
        ]);
        $this->loadModel('TBLMStaff');
        $this->loadModel('TBLMRegion');

        $region_opt = $this->TBLMRegion->getRegionIDs();
        $this->set('region_opt', $region_opt);

    }

    /**
     * @param array $tblmstaff
     * @return bool
     */
    public function isAuthorized($tblmstaff = array())
    {
        if (true === isset($tblmstaff['Position']) && $tblmstaff['Position'] === 'Admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $password
     * @return bool
     */
    public function chkPassword($password)
    {
        if (CST_PASSWORD == $password) {
            return 1;
        } else {
            return 0;
        }
    }

    public function beforeFilter(Event $event)
    {
        // public action send report to mail admin
        $this->Auth->allow(array('prefix' => 'admin', 'controller' => 'schedule', 'action' => 'sendReportToMail'));

        $cookie = $this->Cookie->read('remember_me_cookie');
        $this->set('cookie', $cookie);
        // write Position
        $staffid = $this->Auth->user('StaffID');
        $staff = TableRegistry::getTableLocator()->get('tblMStaff')->find()->where(['StaffID' => $staffid])->first();
        $this->set('auth', $staff);

         // get language
         if ($this->request->session()->check('Config.sort')) {
            $sort = $this->request->session()->read('Config.sort');
            $this->set('sort', $sort);
        } else {
            $this->request->session()->write('Config.sort', '');
        }

         // check Disk
        $Bytes = disk_free_space("./");
        $dataSize = $this->Common->dataSize($Bytes);
        $this->set('dataSize', $dataSize);
    }
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     *
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * @param $data
     * @return \Cake\Http\Response
     */
    public function responseJson($data)
    {
        return $this->response->withType("application/json")->withStringBody(json_encode($data));
    }
}
