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

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;

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
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
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
        $this->loadComponent('Cookie');
        $this->loadComponent('Common');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Cookie' => [
                    'fields' => [
                        'username' => 'StaffID',
                        'password' => 'Password',
                    ],
                    'userModel' => 'TBLMStaff',
                    // scope is deprecated, use 'finder' instead
                    //'scope' => ['QuitJobDate IS NULL'],
                    'finder' => 'auth',
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
                    'finder' => 'auth',
                ],
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            'storage' => [
                'className' => 'Session',
                'key' => 'Auth.User',
            ],
            // If unauthorized, return them to page they were just on
            // 'unauthorizedRedirect' => $this->referer()
        ]);

        $this->loadModel('TBLMStaff');
    }

    public function beforeFilter(Event $event)
    {
        // set cookie options
        $this->Cookie->key = 'qSI232qs*&sXOw!adre@34SAv!@*(XSL#$%)asGb$@11~&#95;+!@#HKis~#^';
        $this->Cookie->httpOnly = true;

        $cookie = $this->Cookie->read('remember_me_cookie');
        $this->set('cookie', $cookie);

        //get Basic Information
        $staffId = $this->Auth->user('StaffID');
        if ($staffId) {
            $staff = $this->TBLMStaff->find()->where(['StaffID' => $staffId])->first();
            $this->set('staff', $staff);
        }

        // get language
        if ($this->request->session()->check('Config.language')) {
            I18n::setLocale($this->request->session()->read('Config.language'));
        } else {
            $this->request->session()->write('Config.language', 'vn_VN');
        }
    }

    public function changeLanguage($language = null)
    {
        if ($language != null && in_array($language, ['en_US', 'jp_JP', 'vn_VN'])) {
            $this->request->session()->write('Config.language', $language);
            return $this->redirect($this->referer());
        } else {
            $this->request->session()->write('Config.language', 'vn_VN');
            return $this->redirect($this->referer());
        }
    }
}
