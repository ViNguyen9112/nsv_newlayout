<?php

namespace App\Controller\Admin;

use Cake\Event\Event;


class UserController extends AppController
{
    public $components = array('Cookie');

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //not use layout
        $this->viewBuilder()->setLayout(false);

        //allow access logout
        $this->Auth->allow(['logout']);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $has_cookie = $this->Cookie->read('remember_me_cookie');
        if ($has_cookie) {
            $this->request->getData()['StaffID'] = $has_cookie['StaffID'];
            $this->request->getData()['Password'] = $has_cookie['Password'];
        }
    }

    public function login()
    {
        if ($this->request->is('post')) {
            // dd($this->request->getData());
            $params = $this->request->getData();

            if ($params['StaffID'] == '' || $params['Password'] == '') {
                $this->Flash->error(__('Please enter your ID or password !'), ['key' => 'auth']);
            } else {
                $user = $this->Auth->identify();

                if ($user) {
                    $position = array_merge(\Constants::$positions['supper_admin'], \Constants::$positions['admin'],  \Constants::$positions['supper_leader']);
                    $position_allow = array_map('strtolower', $position);
                    if(in_array(strtolower($user['Position']), $position_allow)){
                        // did they select the remember me checkbox?
                        if (@$params['remember_me'] == 1) {
                            // remove "remember me checkbox"
                            unset($params['remember_me']);
                            unset($params['Sign_In']);
                            //unset($params['GoogleMapLink']);
                            // write the cookie
                            $this->Cookie->write('remember_me_cookie', $params);
                            // write role
                            $this->Cookie->write('remember_me_cookie.role', 'admin');
                        } else {
                            $this->Cookie->delete('remember_me_cookie');
                        }

                        $this->Auth->setUser($user);
                        //return $this->redirect($this->Auth->redirectUrl());
                        return $this->redirect("/admin/schedule");
                    }
                    else{
                        $this->Flash->error(__('You are not authorized to access that location.'), ['key' => 'auth']);
                    }
                } else {
                    $this->Flash->error(__('ID or Password is incorrect.'), ['key' => 'auth']);
                }
            }
        }
    }

    public function logout()
    {
        $session = $this->request->getSession();
        $session->destroy();
        $this->Cookie->delete('remember_me_cookie');
        $this->Flash->success(__('You successfully have loged out!'));
        return $this->redirect($this->Auth->logout());
    }
}
