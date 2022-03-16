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

namespace App\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setLayout('login');
        parent::beforeRender($event);

        // $has_cookie = $this->Cookie->read('remember_me_cookie_account');
        // if ($has_cookie) {
        //     $this->request->getData()['StaffID'] = $has_cookie['StaffID'];
        //     $this->request->getData()['Password'] = $has_cookie['Password'];
        // }
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $params = $this->request->getData();

            if ($params['StaffID'] == '' || $params['Password'] == '') {
                $this->Flash->error(__('Please enter your ID or password !'), ['key' => 'auth']);
            } else {
                $user = $this->Auth->identify();
                if($user){
                    $positions = array_merge(\Constants::$positions['supper_leader'],  \Constants::$positions['leader']);
                    $position_allow = array_map('strtolower', $positions);
                    if(in_array(strtolower($user['Position']), $position_allow)){
                        // did they select the remember me checkbox?
                        if (@$params['remember_me'] == 1) {
                            // remove "remember me checkbox"
                            $data = [
                                'StaffID' => $params['StaffID'],
                                'Password' => $params['Password']
                            ];
                            // write the cookie
                            $this->Cookie->write('remember_me_cookie', $data);
                            // write role
                            $this->Cookie->write('remember_me_cookie.role', 'user');
                        } else {
                            $this->Cookie->delete('remember_me_cookie');
                        }

                        //set session
                        $this->request->session()->write('Config.language', $params['Language']);
                        $this->Auth->setUser($user);
                        return $this->redirect($this->Auth->redirectUrl());
                    } else {
                        if($params['Language'] == 'vn_VN'){
                            $this->set('flash','Chỉ Lãnh đạo khu vực và lãnh đạo mới có thể đăng nhập vào điện thoại.');
                        } else if($params['Language'] == 'en_US'){
                            $this->set('flash','Only Area leader and Leaders can login to mobile.');
                        } else {
                            $this->set('flash','モバイルは、エリアリーダーとリーダーのみ、ログインできます。');
                        }

                        $this->set('StaffID',$params['StaffID']);
                        $this->set('Password',$params['Password']);
                        $this->set('Language',$params['Language']);
                    }

                } else {
                    if($params['Language'] == 'vn_VN'){
                        $this->set('flash','Tên người dùng hoặc mật khẩu chưa chính xác.');
                    } else if($params['Language'] == 'en_US'){
                        $this->set('flash','Your ID or Password is incorrect.');
                    } else {
                        $this->set('flash','ユーザIDまたはパスワードが不正です.');
                    }

                    $this->set('StaffID',$params['StaffID']);
                    $this->set('Password',$params['Password']);
                    $this->set('Language',$params['Language']);
                }
            }

        }
    }

    public function logout()
    {
        $session = $this->request->getSession();
        $session->destroy();

        // clear the cookie (if it exists) when logging out
        $this->Cookie->delete('remember_me_cookie');

        return $this->redirect($this->Auth->logout());
    }
}
